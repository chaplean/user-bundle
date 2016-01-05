<?php

namespace Chaplean\Bundle\UserBundle\Handler;

use Chaplean\Bundle\UserBundle\Doctrine\UserManager;
use Chaplean\Bundle\UserBundle\Model\AbstractUser;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

/**
 * AuthenticationHandler.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     0.1.0
 */
abstract class AuthenticationHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var Session
     */
    protected $userManager;

    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @var TwigEngine
     */
    protected $template;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * AuthenticationHandler constructor.
     *
     * @param Registry    $registry
     * @param Router      $router
     * @param Session     $session
     * @param UserManager $userManager
     * @param Translator  $translator
     * @param TwigEngine  $template
     * @param array       $parameters
     */
    public function __construct(Registry $registry, Router $router, Session $session, UserManager $userManager, Translator $translator, TwigEngine $template, array $parameters)
    {
        $this->em          = $registry->getManager();
        $this->router      = $router;
        $this->session     = $session;
        $this->userManager = $userManager;
        $this->translator  = $translator;
        $this->template    = $template;
        $this->parameters  = $parameters;
    }

    /**
     * @param Request        $request
     * @param TokenInterface $token
     *
     * @return RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        /** @var AbstractUser $user */
        $user = $token->getUser();

        $user->setLastLogin(new \DateTime('now'));

        // reset token
        if ($user->isEnabled() && $user->getConfirmationToken() != null) {
            $this->userManager->cleanUser($user);
            $this->userManager->updateUser($user);
        } else {
            $this->em->persist($user);
            $this->em->flush();
        }

        $redirection = $this->session->get('_security.main.target_path');

        if (empty($redirection)) {
            $redirection = $this->router->generate($this->parameters['controller']['index_route'], array(), UrlGenerator::ABSOLUTE_PATH);
        }

        return $redirection;
    }

    /**
     * @param Request                 $request
     * @param AuthenticationException $exception
     *
     * @return JsonResponse|RedirectResponse
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return str_replace('.', '', str_replace(' ', '_', strtolower($exception->getMessage())));
    }
}
