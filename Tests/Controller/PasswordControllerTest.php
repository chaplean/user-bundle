<?php

namespace Tests\Chaplean\Bundle\UserBundle\Controller;

use Chaplean\Bundle\UnitBundle\Test\LogicalTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PasswordControllerTest.
 *
 * @package   Tests\Chaplean\Bundle\UserBundle\Controller
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2016 Chaplean (http://www.chaplean.coop)
 * @since     4.0.0
 */
class PasswordControllerTest extends LogicalTestCase
{
    /**
     * @covers \Chaplean\Bundle\UserBundle\Controller\PasswordController::requestResetPasswordFormAction
     *
     * @return void
     */
    public function testCanSeeRequestPasswordResetPageNotLoggedIn()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/password/request_reset'
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Controller\PasswordController::requestResetPasswordFormAction
     *
     * @return void
     */
    public function testCantSeeRequestPasswordResetPageLoggedIn()
    {
        $user = $this->getReference('user-1');

        $client = static::createClient();
        $this->authenticate($user, $client);
        $client->request(
            'GET',
            '/password/request_reset'
        );

        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Controller\PasswordController::requestResetPasswordFormAction
     *
     * @return void
     */
    public function testCanSeeSetPasswordPageNotLoggedInWithValidToken()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/password/set',
            array(
                'email' => 'user-1@test.com',
                'token' => '42'
            )
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Controller\PasswordController::setPasswordFormAction
     *
     * @return void
     */
    public function testCantSeeSetPasswordPageNotLoggedInWithInvalidToken()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/password/set',
            array(
                'email' => 'user-1@test.com',
                'token' => '00'
            )
        );

        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Controller\PasswordController::setPasswordFormAction
     *
     * @return void
     */
    public function testCantSeeSetPasswordPageLoggedInWithValidToken()
    {
        $user = $this->getReference('user-1');

        $client = static::createClient();
        $this->authenticate($user, $client);
        $client->request(
            'GET',
            '/password/set',
            array(
                'email' => 'user-1@test.com',
                'token' => '00'
            )
        );

        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }
}
