<?php

namespace Tests\Chaplean\Bundle\UserBundle\Command;

use Chaplean\Bundle\UnitBundle\Test\FunctionalTestCase;

/**
 * Class ChapleanUserCreateCommandTest.
 *
 * @package   Tests\Chaplean\Bundle\UserBundle\Command
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.coop)
 * @since     2.0.0
 */
class ChapleanUserCreateCommandTest extends FunctionalTestCase
{
    /**
     * @return void
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Command\ChapleanUserCreateCommand::configure
     * @covers \Chaplean\Bundle\UserBundle\Command\ChapleanUserCreateCommand::execute
     *
     * @return void
     */
    public function testCreateUser()
    {
        $this->assertCount(2, $this->em->getRepository('ChapleanUserBundle:DummyUser')->findAll());

        $this->runCommand(
            'chaplean:user:create',
            [
                'email'     => 'test@test.com',
                'password'  => 'test',
            ],
            true
        );

        $this->assertCount(3, $this->em->getRepository('ChapleanUserBundle:DummyUser')->findAll());
    }
}
