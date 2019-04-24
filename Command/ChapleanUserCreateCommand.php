<?php

namespace Chaplean\Bundle\UserBundle\Command;

use Chaplean\Bundle\UserBundle\Event\ChapleanUserCreatedEvent;
use Chaplean\Bundle\UserBundle\Model\User;
use Chaplean\Bundle\UserBundle\Model\UserManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ChapleanUserCreateCommand.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2015 Chaplean (https://www.chaplean.coopn.coop)
 * @since     2.0.0
 */
class ChapleanUserCreateCommand extends ContainerAwareCommand
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('chaplean:user:create')
            ->setDescription('Create a user.')
            ->setDefinition(
                [
                    new InputArgument('email', InputArgument::REQUIRED, 'The email'),
                    new InputArgument('password', InputArgument::REQUIRED, 'The password'),
                ]
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        /** @var UserManager $userManager */
        $userManager = $this->getContainer()
            ->get('chaplean_user.user_manager');

        /** @var User $user */
        $user = $userManager->createUser();

        $user->setEmail($email);
        $user->setPlainPassword($password);
        $user->setEnabled(true);
        $user->setDateAdd(new \DateTime());

        $userManager->updateUser($user);

        $dispatcher = $this->getContainer()
            ->get('event_dispatcher');
        $dispatcher->dispatch(ChapleanUserCreatedEvent::NAME, new ChapleanUserCreatedEvent($user));

        $this->getContainer()
            ->get('doctrine')
            ->getManager()
            ->flush();

        $output->writeln(sprintf('Created user <comment>%s</comment>', $email));
    }
}
