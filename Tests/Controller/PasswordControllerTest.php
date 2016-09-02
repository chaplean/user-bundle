<?php

namespace Tests\Chaplean\Bundle\UserBundle\Controller;

use Chaplean\Bundle\UnitBundle\Test\LogicalTestCase;
use FOS\RestBundle\Util\Codes;

/**
 * Class PasswordControllerTest.
 *
 * @package   Tests\Chaplean\Bundle\UserBundle\Controller
 * @author    Matthias - Chaplean <matthias@chaplean.com>
 * @copyright 2014 - 2016 Chaplean (http://www.chaplean.com)
 * @since     4.0.0
 */
class PasswordControllerTest extends LogicalTestCase
{
    /**
     * @return void
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
    }

    /**
     * @return void
     */
    public function testCanSeeRequestPasswordResetPageNotLoggedIn()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/password/request_reset'
        );

        $this->assertEquals(Codes::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
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

        $this->assertEquals(Codes::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    /**
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

        $this->assertEquals(Codes::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
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

        $this->assertEquals(Codes::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    /**
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

        $this->assertEquals(Codes::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }
}
