<?php

namespace Chaplean\Bundle\UserBundle\Handler;

use Chaplean\Bundle\UserBundle\Doctrine\UserManager;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * AuthenticationHandler.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     0.1.0
 */
class AuthenticationHandlerHttp extends AuthenticationHandler
{
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
        parent::__construct($registry, $router, $session, $userManager, $translator, $template, $parameters);
    }

    /**
     * @param Request        $request
     * @param TokenInterface $token
     *
     * @return RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $redirection = parent::onAuthenticationSuccess($request, $token);

        return new RedirectResponse($redirection, 302);
    }

    /**
     * @param Request                 $request
     * @param AuthenticationException $exception
     *
     * @return RedirectResponse
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $messageError = parent::onAuthenticationFailure($request, $exception);

        $this->session->getFlashBag()->add('error', $messageError);
        return new RedirectResponse($this->router->generate($this->parameters['controller']['login_route'], array(), UrlGenerator::ABSOLUTE_PATH));
    }
}
