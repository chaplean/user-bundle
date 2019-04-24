<?php

namespace Chaplean\Bundle\UserBundle\Email;

use Chaplean\Bundle\UserBundle\Model\UserInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class UserPasswordEmail.
 *
 * @package   Chaplean\Bundle\UserBundle\Email
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coopn.coop)
 */
class UserPasswordEmail implements EmailInterface
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var String
     */
    protected $subject;

    /**
     * @var String
     */
    protected $body;

    /**
     * @var String
     */
    protected $link;

    /**
     * @var UserInterface
     */
    protected $user;

    /**
     * UserPasswordEmail constructor.
     *
     * @param TranslatorInterface $translator
     * @param EngineInterface     $templating
     * @param RouterInterface     $router
     */
    public function __construct(TranslatorInterface $translator, EngineInterface $templating, RouterInterface $router)
    {
        $this->translator = $translator;
        $this->templating = $templating;
        $this->router = $router;
    }

    /**
     * @param $subject
     *
     * @return void
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @param $subject
     *
     * @return void
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @param $subject
     *
     * @return void
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @param UserInterface $user
     *
     * @return void
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * @return void
     */
    public function getEmail()
    {
        $token = $this->user->getConfirmationToken();

        $message = new \Swift_Message();
        $message->setContentType('text/html');
        $message->setSubject($this->translator->trans($this->subject));
        $message->setTo($this->user->getEmail());
        $message->setBody(
            $this->templating->render(
                $this->body,
                [
                    'user' => $this->user,
                    'link' => $this->router->generate($this->link, ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL)
                ]
            )
        );

        return $message;
    }
}
