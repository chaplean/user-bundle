<?php

namespace Chaplean\Bundle\UserBundle\Utility;

use Chaplean\Bundle\UserBundle\Email\UserPasswordEmail;
use Chaplean\Bundle\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;

/**
 * Registration.php.
 *
 * @package   Chaplean\Bundle\UserBundle\Utility
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2015 Chaplean (https://www.chaplean.coopn.coop)
 * @since     0.1.0
 */
class RegistrationUtility
{
    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var \Swift_Mailer
     */
    protected $swiftMailer;
    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @var UserPasswordEmail
     */
    protected $userPasswordEmail;

    /**
     * RegistrationUtility constructor.
     *
     * @param array               $parameters
     * @param \Swift_Mailer       $swiftMailer
     * @param UserPasswordEmail   $userPasswordEmail
     */
    public function __construct(array $parameters, \Swift_Mailer $swiftMailer, UserPasswordEmail $userPasswordEmail)
    {
        $this->parameters = $parameters;
        $this->swiftMailer = $swiftMailer;
        $this->userPasswordEmail = $userPasswordEmail;
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
        $link = $this->parameters['controller']['register_password_route'];

        $this->userPasswordEmail->setSubject($emailing['subject']);
        $this->userPasswordEmail->setBody($emailing['body']);
        $this->userPasswordEmail->setLink($link);
        $this->userPasswordEmail->setUser($user);
        $this->swiftMailer->send($this->userPasswordEmail->getEmail());
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
        $link = $this->parameters['controller']['resetting_password_route'];

        if ($link === null) {
            $link = $this->parameters['controller']['register_password_route'];
        }

        $this->userPasswordEmail->setSubject($emailing['subject']);
        $this->userPasswordEmail->setBody($emailing['body']);
        $this->userPasswordEmail->setLink($link);
        $this->userPasswordEmail->setUser($user);
        $this->swiftMailer->send($this->userPasswordEmail->getEmail());
    }
}
