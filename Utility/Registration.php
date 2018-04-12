<?php

namespace Chaplean\Bundle\UserBundle\Utility;

use Chaplean\Bundle\UserBundle\Doctrine\User;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Routing\Router;

/**
 * Registration.php.
 *
 * @package   Chaplean\Bundle\UserBundle\Utility
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.coop)
 * @since     0.1.0
 */
class Registration
{
    protected $serviceContainer;
    protected $translator;

    /**
     * Constructor.
     *
     * @param Container $serviceContainer serviceContainer.
     */
    public function __construct($serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
        $this->translator = $serviceContainer->get('translator');
    }

    /**
     * Send the registration mail with the link to activate the account.
     *
     * @param User $user User to send the mail to.
     *
     * @return void
     */
    public function sendRegistrationMailForUser(User $user)
    {
        $this->sendMail($this->translator->trans('register.mail.subject'), $user, 'ChapleanUserBundle:Email:register-password.txt.twig');
    }

    /**
     * Send the registration mail with the link to activate the account.
     *
     * @param User $user User to send the mail to.
     *
     * @return void
     */
    public function sendResettingMailForUser(User $user)
    {
        $this->sendMail($this->translator->trans('forgot.mail.subject'), $user, 'ChapleanUserBundle:Email:resetting-password.txt.twig');
    }

    /**
     * Return mail to send.
     *
     * @param string $subject
     * @param User   $user
     * @param string $view
     *
     * @return void
     */
    private function sendMail($subject, $user, $view)
    {
        $token = $user->getConfirmationToken();

        /** @var \Symfony\Bundle\TwigBundle\TwigEngine $templateRenderer */
        $templateRenderer = $this->serviceContainer->get('templating');

        /** @var Router $router */
        $router = $this->serviceContainer->get('router');

        $message = new \Swift_Message();
        $message->setContentType('text/html');
        $message->setSubject($subject);
        $message->setTo($user->getEmail());
        $message->setBody(
            $templateRenderer->render(
                $view,
                [
                    'link' => $router->generate('chaplean_user_password_set_password', ['token' => $token], true)
                ]
            )
        );

        $this->serviceContainer->get('swiftmailer.mailer')->send($message);
    }
}
