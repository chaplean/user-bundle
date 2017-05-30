<?php

namespace Chaplean\Bundle\UserBundle\Command;

use Chaplean\Bundle\UserBundle\Doctrine\User;
use Chaplean\Bundle\UserBundle\Event\ChapleanUserDeletedEvent;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class ChapleanUserDeleteCommand.
 *
 * @package   Chaplean\Bundle\UserBundle\Command
 * @author    Matthias - Chaplean <matthias@chaplean.com>
 * @copyright 2014 - 2017 Chaplean (http://www.chaplean.com)
 * @since     5.0.1
 */
class ChapleanUserDeleteCommand extends ContainerAwareCommand
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('chaplean:user:delete')
            ->setDescription('Deletes the given user')
            ->setDefinition([new InputArgument('email', InputArgument::REQUIRED, 'Email of the user to delete')]);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();

        $email = $input->getArgument('email');
        $repository = $em->getRepository(User::class);

        /** @var User $user */
        $user = $repository->findOneBy(['email' => $email]);

        if ($user === null) {
            $output->writeln("Can't find any user with the email " . $email . ". Aborting.");

            return;
        }

        $questionText = 'Are you sure you want to delete the user: ' . (string) $user . " ?\n";
        $question = new ConfirmationQuestion($questionText, false, '/^(yes|YES)$/');

        if (!$this->getHelper('question')->ask($input, $output, $question)) {
            $output->writeln('Command aborted, no user deleted.');

            return;
        }

        $userManager = $this->getContainer()->get('chaplean_user.user_manager');
        $userManager->deleteUser($user, false);

        $dispatcher = $this->getContainer()->get('event_dispatcher');
        $dispatcher->dispatch(ChapleanUserDeletedEvent::NAME, new ChapleanUserDeletedEvent($user));

        $em->flush();

        $output->writeln('Done.');
    }
}
