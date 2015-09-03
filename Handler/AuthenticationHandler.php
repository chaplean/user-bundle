<?php

namespace Chaplean\Bundle\UserBundle\Handler;

use Chaplean\Bundle\UserBundle\Doctrine\UserManager;
use Chaplean\Bundle\UserBundle\Doctrine\User;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
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
class AuthenticationHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface
{
    protected $router;
    protected $security;
    protected $container;

    /**
     * @param RouterInterface $router
     * @param TokenStorage    $security
     * @param Container       $container
     */
    public function __construct(RouterInterface $router, TokenStorage $security, $container)
    {
        $this->router = $router;
        $this->security = $security;
        $this->container = $container;
    }

    /**
     * @param Request        $request
     * @param TokenInterface $token
     *
     * @return JsonResponse|RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        /** @var User $user */
        $user = $this->security->getToken()->getUser();

        $user->setLastLogin(new \DateTime('now'));

        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->persist($user);

        // reset token
        if ($user->isEnabled() && $user->getConfirmationToken() != null) {
            /** @var UserManager $userManager */
            $userManager = $this->container->get('chaplean_user.user_manager');

            $userManager->cleanUser($user);
            $userManager->updateUser($user, false);
        }

        $em->flush();

        $referer = $request->headers->get('referer');

        if (empty($referer)) {
            $referer = $this->router->generate($this->container->getParameter('chaplean_user.controller.index_path'));
        }

        if ($request->isXmlHttpRequest()) {
            $result = array('success' => true, 'message' => $referer);
            $response = new JsonResponse($result);

            return $response;
        } else {
            return new RedirectResponse($referer);
        }
    }

    /**
     * @param Request                 $request
     * @param AuthenticationException $exception
     *
     * @return JsonResponse|RedirectResponse
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        var_dump($exception->getMessage());
        /** @var Translator $translator */
        $translator = $this->container->get('translator');

        $messageError = str_replace('.', '', str_replace(' ', '_', strtolower($exception->getMessage())));
        $messageError = $translator->trans('login.' . $messageError);

        if ($request->isXmlHttpRequest()) {
            $result = array('success' => false, 'message' => $messageError);
            $response = new JsonResponse($result);

            return $response;
        } else {
            $request->getSession()->getFlashBag()->add('error', $messageError);

            return new RedirectResponse($request->headers->get('referer'));
        }
    }
}
