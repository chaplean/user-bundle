<?php

namespace Chaplean\Bundle\UserBundle\Command;

use Chaplean\Bundle\UserBundle\Doctrine\UserManager;
use Chaplean\Bundle\UserBundle\Model\AbstractUser;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ChapleanUserCreateCommand.php.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     2.0.0
 */
class ChapleanUserCreateCommand extends ContainerAwareCommand
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('chaplean:user:create')
            ->setDescription('Create a user.')
            ->setDefinition(array(
                new InputArgument('email', InputArgument::REQUIRED, 'The email'),
                new InputArgument('password', InputArgument::REQUIRED, 'The password'),
                new InputArgument('firstname', InputArgument::REQUIRED, 'The firstname'),
                new InputArgument('lastname', InputArgument::REQUIRED, 'The lastname'),
            ));
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $email     = $input->getArgument('email');
        $password  = $input->getArgument('password');
        $firstname = $input->getArgument('firstname');
        $lastname  = $input->getArgument('lastname');

        /** @var UserManager $userManager */
        $userManager = $this->getContainer()->get('chaplean_user.user_manager');

        /** @var AbstractUser $user */
        $user = $userManager->createUser();
        $user->setEmail($email);
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setPlainPassword($password);
        $user->setEnabled(true);
        $user->setDateAdd(new \DateTime());

        $userManager->updateUser($user);

        $output->writeln(sprintf('Created user <comment>%s</comment>', $email));
    }
}