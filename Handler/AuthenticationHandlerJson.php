<?php

namespace Chaplean\Bundle\UserBundle\Handler;

use Chaplean\Bundle\UserBundle\Model\UserManager;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * AuthenticationHandler.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2015 Chaplean (https://www.chaplean.coopn.coop)
 * @since     0.1.0
 */
class AuthenticationHandlerJson extends AuthenticationHandler
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
     * @return JsonResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $redirection = parent::onAuthenticationSuccess($request, $token);

        return new JsonResponse(['redirect' => $redirection]);
    }

    /**
     * @param Request                 $request
     * @param AuthenticationException $exception
     *
     * @return JsonResponse
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $messageError = parent::onAuthenticationFailure($request, $exception);

        return new JsonResponse(['error' => $messageError], 400);
    }
}
