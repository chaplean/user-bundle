<?php

namespace Chaplean\Bundle\UserBundle\Utility;

use Chaplean\Bundle\UserBundle\Model\UserInterface;
use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Registration.php.
 *
 * @package   Chaplean\Bundle\UserBundle\Utility
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.coop)
 * @since     0.1.0
 */
class RegistrationUtility
{
    /**
     * @var array
     */
    protected $parameters;
    /**
     * @var Router
     */
    protected $router;
    /**
     * @var \Swift_Mailer
     */
    protected $swiftMailer;
    /**
     * @var Translator
     */
    protected $translator;
    /**
     * @var TwigEngine
     */
    protected $templating;

    /**
     * RegistrationUtility constructor.
     *
     * @param array               $parameters
     * @param RouterInterface     $router
     * @param \Swift_Mailer       $swiftMailer
     * @param TranslatorInterface $translator
     * @param EngineInterface     $templating
     */
    public function __construct(array $parameters, RouterInterface $router, \Swift_Mailer $swiftMailer, TranslatorInterface $translator, EngineInterface $templating)
    {
        $this->parameters = $parameters;
        $this->router = $router;
        $this->swiftMailer = $swiftMailer;
        $this->translator = $translator;
        $this->templating = $templating;
    }

    /**
     * Send the registration mail with the link to activate the account.
     *
     * @param UserInterface $user User to send the mail to.
     *
     * @return void
     */
    public function sendRegistrationMailForUser(UserInterface $user): void
    {
        $emailing = $this->parameters['emailing']['register'];
        $this->sendMail($this->translator->trans($emailing['subject']), $user, $emailing['body']);
    }

    /**
     * Send the registration mail with the link to activate the account.
     *
     * @param UserInterface $user User to send the mail to.
     *
     * @return void
     */
    public function sendResettingMailForUser(UserInterface $user): void
    {
        $emailing = $this->parameters['emailing']['resetting'];
        $this->sendMail($this->translator->trans($emailing['subject']), $user, $emailing['body']);
    }

    /**
     * Return mail to send.
     *
     * @param string        $subject
     * @param UserInterface $user
     * @param string        $view
     *
     * @return void
     */
    private function sendMail(string $subject, UserInterface $user, string $view): void
    {
        $token = $user->getConfirmationToken();
        $link = $this->parameters['controller']['set_password_route'];

        $message = new \Swift_Message();
        $message->setContentType('text/html');
        $message->setSubject($subject);
        $message->setTo($user->getEmail());
        $message->setBody(
            $this->templating->render(
                $view,
                [
                    'user' => $user,
                    'link' => $this->router->generate($link, ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL)
                ]
            )
        );

        $this->swiftMailer->send($message);
    }
}
