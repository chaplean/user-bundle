<?php

namespace Chaplean\Bundle\UserBundle\Tests\Command;

use Chaplean\Bundle\UnitBundle\Test\LogicalTest;

/**
 * Class ChapleanUserCreateCommandTest.
 *
 * @package   Chaplean\Bundle\UserBundle\Tests\Command
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     2.0.0
 */
class ChapleanUserCreateCommandTest extends LogicalTest
{
    /**
     * @return void
     */
//    public static function setUpBeforeClass()
//    {
//    }

    /**
     * @return void
     */
    public function setUp()
    {
    }

    /**
     * @return void
     */
    public function testCreateUser()
    {
        $this->assertCount(0, $this->em->getRepository('ChapleanUserBundle:DummyUser')->findAll());

        $this->runCommand(
            'chaplean:user:create',
            array(
                'email'     => 'test@test.com',
                'password'  => 'test',
                'firstname' => 'foo',
                'lastname'  => 'bar',
            )
        );

//        $this->assertEquals('Created user test@test.com', $response);
        $this->assertCount(1, $this->em->getRepository('ChapleanUserBundle:DummyUser')->findAll());
    }
}
