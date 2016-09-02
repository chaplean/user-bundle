<?php

namespace Tests\Chaplean\Bundle\UserBundle\Controller\Rest;

use Chaplean\Bundle\UnitBundle\Test\LogicalTestCase;
use FOS\RestBundle\Util\Codes;

/**
 * Class PasswordControllerTest.
 *
 * @package   Tests\Chaplean\Bundle\UserBundle\Controller\Rest
 * @author    Matthias - Chaplean <matthias@chaplean.com>
 * @copyright 2014 - 2016 Chaplean (http://www.chaplean.com)
 * @since     4.0.0
 */
class PasswordControllerTest extends LogicalTestCase
{
    /**
     * @return void
     */
    public function testPostRequestResetPasswordActionSendResetEmailIfNotLoggedWithValidEmail()
    {
        /** @var User $user */
        $user = $this->getReference('user-1');

        $client = $this->createRestClient();
        $response = $client->request(
            'POST',
            '/api/password/request_reset',
            array(),
            array(),
            array(
                'email' => 'user-1@test.com'
            )
        );

        $this->assertEquals(Codes::HTTP_NO_CONTENT, $response->getStatusCode());

        $messages = $this->readMessages();
        $this->assertCount(1, $messages);
    }

    /**
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
            array(),
            array(),
            array(
                'email' => 'user-1@test.com'
            )
        );

        $this->assertEquals(Codes::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testPostRequestResetPasswordActionFailsIfNotLoggedWithInvalidEmail()
    {
        /** @var User $user */
        $user = $this->getReference('user-1');

        $client = $this->createRestClient();
        $response = $client->request(
            'POST',
            '/api/password/request_reset',
            array(),
            array(),
            array(
                'email' => 'invalid-email@test.com'
            )
        );

        $this->assertEquals(Codes::HTTP_NO_CONTENT, $response->getStatusCode());

        $messages = $this->readMessages();
        $this->assertEmpty($messages);
    }

    /**
     * @return void
     */
    public function testPostSetPasswordUpdatesPasswordIfValidDataAndNotLoggedIn()
    {
         /** @var User $user */
        $user = $this->getReference('user-1');

        $client = $this->createRestClient();
        $response = $client->request(
            'POST',
            '/api/password/set',
            array(),
            array(),
            array(
                'password' => array(
                    'first'  => 'test!!',
                    'second' => 'test!!',
                ),
                'token'    => '42',
            )
        );

        $this->assertEquals(Codes::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    /**
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
            array(),
            array(),
            array(
                'password' => array(
                    'first'  => 'test!!',
                    'second' => 'test!!',
                ),
                'token'    => '42',
            )
        );

        $this->assertEquals(Codes::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testPostSetPasswordFailsIfInvalidTokenAndNotLoggedIn()
    {
        /** @var User $user */
        $user = $this->getReference('user-1');

        $client = $this->createRestClient();
        $response = $client->request(
            'POST',
            '/api/password/set',
            array(),
            array(),
            array(
                'password' => array(
                    'first'  => 'test!!',
                    'second' => 'test!!',
                ),
                'token'    => '0000',
            )
        );

        $this->assertEquals(Codes::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testPostSetPasswordFailsIfDifferentPasswordsAndNotLoggedIn()
    {
        /** @var User $user */
        $user = $this->getReference('user-1');

        $client = $this->createRestClient();
        $response = $client->request(
            'POST',
            '/api/password/set',
            array(),
            array(),
            array(
                'password' => array(
                    'first'  => 'test!!',
                    'second' => 'test!!different',
                ),
                'token'    => '42',
            )
        );

        $this->assertEquals(Codes::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testPostSetPasswordFailsIfInvalidPasswordAndNotLoggedIn()
    {
        /** @var User $user */
        $user = $this->getReference('user-1');

        $client = $this->createRestClient();
        $response = $client->request(
            'POST',
            '/api/password/set',
            array(),
            array(),
            array(
                'password' => array(
                    'first'  => 'test',
                    'second' => 'test',
                ),
                'token'    => '42',
            )
        );

        $this->assertEquals(Codes::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
