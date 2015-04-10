<?php
/**
 * Registration.php.
 *
 * @package   Chaplean\Bundle\UserBundle\Utility
 * @author    Matthias - Chaplean <matthias@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     0.1.0
 */

namespace Chaplean\Bundle\UserBundle\Utility;

use Chaplean\Bundle\MailerBundle\lib\classes\Chaplean\Message;
use Chaplean\Bundle\UserBundle\Entity\User;

class Registration
{
    protected $serviceContainer;
    protected $translator;

    /**
     * Constructor.
     *
     * @param Service $serviceContainer serviceContainer.
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
     * @return Message|null
     */
    private function sendMail($subject, $user, $view)
    {
        // set a token for user
        $token = $this->serviceContainer->get('fos_user.util.token_generator')->generateToken();
        $user->setConfirmationToken($token);

        // create email to send with token for enter password
        $templateRenderer = $this->serviceContainer->get('templating');
        $router = $this->serviceContainer->get('router');
        $chapleanMailerConfig = $this->serviceContainer->getParameter('chaplean_mailer');
        $message = new Message($chapleanMailerConfig);
        $message->setContentType('text/html');
        $message->setSubject($subject)
            ->setTo($user->getEmail())
            ->setBody(
                $templateRenderer->render(
                    $view,
                    array(
                        'civility'  => $this->translator->trans('register.builder.civility.'.$user->getTitle().'.abbr'),
                        'firstname' => $user->getFirstname(),
                        'lastname'  => $user->getLastname(),
                        'link'      => $router->generate('chaplean_user_confirm', array('token' => $token), true)
                    )
                )
            );
        $this->serviceContainer->get('swiftmailer.mailer.default')->send($message);
    }
}