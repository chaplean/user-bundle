<?php

namespace Tests\Chaplean\Bundle\UserBundle\Command;

use Chaplean\Bundle\UnitBundle\Test\LogicalTestCase;

/**
 * Class ChapleanUserCreateCommandTest.
 *
 * @package   Tests\Chaplean\Bundle\UserBundle\Command
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     2.0.0
 */
class ChapleanUserCreateCommandTest extends LogicalTestCase
{
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
            ),
            true
        );

        $this->assertCount(1, $this->em->getRepository('ChapleanUserBundle:DummyUser')->findAll());
    }
}
