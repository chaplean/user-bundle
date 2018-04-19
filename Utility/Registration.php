<?php

namespace Chaplean\Bundle\UserBundle\Utility;

use Chaplean\Bundle\UserBundle\Model\User;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Translation\TranslatorInterface;
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
    /**
     * @var Container
     */
    protected $serviceContainer;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * Constructor.
     *
     * @param Container $serviceContainer
     * @param array     $parameters
     *
     * @return void
     * @throws \Exception
     */
    public function __construct($serviceContainer, array $parameters)
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
     * @throws \Exception
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
     * @throws \Exception
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
     * @throws \Exception
     */
    private function sendMail($subject, $user, $view)
    {
        $token = $user->getConfirmationToken();

        /** @var \Symfony\Bundle\TwigBundle\TwigEngine $templateRenderer */
        $templateRenderer = $this->serviceContainer->get('templating');

        $link = $this->parameters['controller']['set_password_route'];

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
                    'link' => $router->generate($link, ['token' => $token], true)
                ]
            )
        );

        $this->serviceContainer->get('swiftmailer.mailer')->send($message);
    }
}
