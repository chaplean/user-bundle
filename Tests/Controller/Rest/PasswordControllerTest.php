<?php

namespace Tests\Chaplean\Bundle\UserBundle\Controller\Rest;

use Chaplean\Bundle\UnitBundle\Entity\User;
use Chaplean\Bundle\UnitBundle\Test\FunctionalTestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PasswordControllerTest.
 *
 * @package   Tests\Chaplean\Bundle\UserBundle\Controller\Rest
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2016 Chaplean (http://www.chaplean.coop)
 * @since     4.0.0
 */
class PasswordControllerTest extends FunctionalTestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @covers \Chaplean\Bundle\UserBundle\Controller\Rest\PasswordController::postRequestResetPasswordAction
     *
     * @return void
     */
    public function testPostRequestResetPasswordActionSendResetEmailIfNotLoggedWithValidEmail()
    {
        $mailer = \Mockery::mock(\Swift_Mailer::class);
        $client = $this->createRestClient();

        $client->getContainer()->set('swiftmailer.mailer', $mailer);
        $mailer->shouldReceive('send')->once()->andReturnNull();

        $response = $client->request(
            'POST',
            '/api/password/request_reset',
            [],
            [],
            [
                'email' => 'user-1@test.com'
            ]
        );

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Controller\Rest\PasswordController::postRequestResetPasswordAction
     *
     * @return void
     */
    public function testPostRequestResetPasswordActionFailsIfLoggedWithValidEmail()
    {
        /** @var User $user */
        $user = $this->getReference('user-1');

        $client = $this->createRestClient();
        $this->authenticate($user);
        $response = $client->request(
            'POST',
            '/api/password/request_reset',
            [],
            [],
            [
                'email' => 'user-1@test.com'
            ]
        );

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Controller\Rest\PasswordController::postRequestResetPasswordAction
     *
     * @return void
     */
    public function testPostRequestResetPasswordActionFailsIfNotLoggedWithInvalidEmail()
    {
        $mailer = \Mockery::mock(\Swift_Mailer::class);
        $client = $this->createRestClient();

        $client->getContainer()->set('swiftmailer.mailer', $mailer);
        $mailer->shouldReceive('send')->never();

        $response = $client->request(
            'POST',
            '/api/password/request_reset',
            [],
            [],
            [
                'email' => 'invalid-email@test.com'
            ]
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals('{"error":"user_not_found"}', $response->getContent());
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Controller\Rest\PasswordController::postRequestResetPasswordAction
     *
     * @return void
     */
    public function testPostRequestResetPasswordActionWithInvalidForm()
    {
        $mailer = \Mockery::mock(\Swift_Mailer::class);
        $client = $this->createRestClient();

        $client->getContainer()->set('swiftmailer.mailer', $mailer);
        $mailer->shouldReceive('send')->never();

        $response = $client->request(
            'POST',
            '/api/password/request_reset',
            [],
            [],
            [
                'emaillll' => 'invalid-email@test.com'
            ]
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals('{"error":"invalid_form"}', $response->getContent());
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Controller\Rest\PasswordController::postSetPasswordAction
     *
     * @return void
     */
    public function testPostSetPasswordUpdatesPasswordIfValidDataAndNotLoggedIn()
    {
        $client = $this->createRestClient();
        $response = $client->request(
            'POST',
            '/api/password/set',
            [],
            [],
            [
                'password' => [
                    'first'  => 'test!!',
                    'second' => 'test!!',
                ],
                'token'    => '42',
            ]
        );

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Controller\Rest\PasswordController::postSetPasswordAction
     *
     * @return void
     */
    public function testPostSetPasswordFailsIfValidDataAndLoggedIn()
    {
        /** @var User $user */
        $user = $this->getReference('user-1');

        $client = $this->createRestClient();
        $this->authenticate($user);
        $response = $client->request(
            'POST',
            '/api/password/set',
            [],
            [],
            [
                'password' => [
                    'first'  => 'test!!',
                    'second' => 'test!!',
                ],
                'token'    => '42',
            ]
        );

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Controller\Rest\PasswordController::postSetPasswordAction
     *
     * @return void
     */
    public function testPostSetPasswordFailsIfInvalidTokenAndNotLoggedIn()
    {
        $client = $this->createRestClient();
        $response = $client->request(
            'POST',
            '/api/password/set',
            [],
            [],
            [
                'password' => [
                    'first'  => 'test!!',
                    'second' => 'test!!',
                ],
                'token'    => '0000',
            ]
        );

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Controller\Rest\PasswordController::postSetPasswordAction
     *
     * @return void
     */
    public function testPostSetPasswordFailsIfDifferentPasswordsAndNotLoggedIn()
    {
        $client = $this->createRestClient();
        $response = $client->request(
            'POST',
            '/api/password/set',
            [],
            [],
            [
                'password' => [
                    'first'  => 'test!!',
                    'second' => 'test!!different',
                ],
                'token'    => '42',
            ]
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Controller\Rest\PasswordController::postSetPasswordAction
     *
     * @return void
     */
    public function testPostSetPasswordFailsIfInvalidPasswordAndNotLoggedIn()
    {
        $client = $this->createRestClient();
        $response = $client->request(
            'POST',
            '/api/password/set',
            [],
            [],
            [
                'password' => [
                    'first'  => 'test',
                    'second' => 'test',
                ],
                'token'    => '42',
            ]
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
