<?php

namespace Chaplean\Bundle\UserBundle\Command;

use Chaplean\Bundle\UserBundle\Doctrine\UserManager;
use Chaplean\Bundle\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UserCommand.
 *
 * @package   Chaplean\Bundle\UserBundle\Command
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     0.1.0
 */
class ChapleanUserCleanCommand extends ContainerAwareCommand
{
    /**
     * Defines how the command works;
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('chaplean:user:clean')->setDescription('clear user account');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var UserManager $userManager */
        $userManager = $this->getContainer()->get('chaplean_user.user_manager');

        // all users
        $users = $userManager->findAll();

        // time of expired account (24h => 3 600 sec x 24 = 86 400 sec)
        $time = 86400;

        /** @var User $user */
        foreach ($users as $user) {
            if ($user->isAccountExpired($time)) {
                $userManager->removeUser($user);
            }
        }

        $output->writeln('Clean done.');
    }
}
