<?php

namespace Tests\Chaplean\Bundle\UserBundle\Command;

use Chaplean\Bundle\UnitBundle\Test\FunctionalTestCase;
use Chaplean\Bundle\UserBundle\Command\ChapleanUserDeleteCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class ChapleanUserDeleteCommandTest.
 *
 * @package   Tests\Chaplean\Bundle\UserBundle\Command
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.coop)
 * @since     2.0.0
 */
class ChapleanUserDeleteCommandTest extends FunctionalTestCase
{
    /**
     * @covers \Chaplean\Bundle\UserBundle\Command\ChapleanUserDeleteCommand::configure
     * @covers \Chaplean\Bundle\UserBundle\Command\ChapleanUserDeleteCommand::execute
     *
     * @return void
     */
    public function testDeleteUser()
    {
        $this->assertCount(2, $this->em->getRepository('ChapleanUserBundle:DummyUser')->findAll());

        $application = new Application();
        $application->add(new ChapleanUserDeleteCommand());

        $command = $application->find('chaplean:user:delete');
        $command->setContainer($this->getContainer());

        $helper = $command->getHelper('question');
        $helper->setInputStream(self::getInputStream("yes\n"));

        $tester = new CommandTester($command);
        $tester->execute(
            [
                'command' => $command->getName(),
                'email'   => 'user-1@test.com'
            ]
        );

        $this->assertContains('Are you sure you want to delete the user: id 1, email user-1@test.com, firstname Test, lastname TEST?', $tester->getDisplay());
        $this->assertContains('Done.', $tester->getDisplay());
        $this->assertCount(1, $this->em->getRepository('ChapleanUserBundle:DummyUser')->findAll());
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Command\ChapleanUserDeleteCommand::configure
     * @covers \Chaplean\Bundle\UserBundle\Command\ChapleanUserDeleteCommand::execute
     *
     * @return void
     */
    public function testDeleteUserInvalidConfirmationInput()
    {
        $this->assertCount(2, $this->em->getRepository('ChapleanUserBundle:DummyUser')->findAll());

        $application = new Application();
        $application->add(new ChapleanUserDeleteCommand());

        $command = $application->find('chaplean:user:delete');
        $command->setContainer($this->getContainer());

        $helper = $command->getHelper('question');
        $helper->setInputStream(self::getInputStream("y\n"));

        $tester = new CommandTester($command);
        $tester->execute(
            [
                'command' => $command->getName(),
                'email'   => 'user-1@test.com'
            ]
        );

        $this->assertContains('Command aborted, no user deleted.', $tester->getDisplay());
        $this->assertCount(2, $this->em->getRepository('ChapleanUserBundle:DummyUser')->findAll());
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Command\ChapleanUserDeleteCommand::configure
     * @covers \Chaplean\Bundle\UserBundle\Command\ChapleanUserDeleteCommand::execute
     *
     * @return void
     */
    public function testDeleteUserCantFindUser()
    {
        $this->assertCount(2, $this->em->getRepository('ChapleanUserBundle:DummyUser')->findAll());

        $application = new Application();
        $application->add(new ChapleanUserDeleteCommand());

        $command = $application->find('chaplean:user:delete');
        $command->setContainer($this->getContainer());

        $helper = $command->getHelper('question');
        $helper->setInputStream(self::getInputStream("yes\n"));

        $tester = new CommandTester($command);
        $tester->execute(
            [
                'command' => $command->getName(),
                'email'   => 'no-existing@user.com'
            ]
        );

        $this->assertContains("Can't find any user with the email no-existing@user.com. Aborting.", $tester->getDisplay());
        $this->assertCount(2, $this->em->getRepository('ChapleanUserBundle:DummyUser')->findAll());
    }

    /**
     * @param string $input
     *
     * @return resource
     */
    public static function getInputStream($input)
    {
        $stream = fopen('php://memory', 'br+', false);
        fwrite($stream, $input);
        rewind($stream);

        return $stream;
    }
}
