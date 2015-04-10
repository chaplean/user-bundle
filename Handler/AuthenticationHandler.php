<?php

namespace Chaplean\Bundle\UserBundle\Handler;

use Chaplean\Bundle\UserBundle\Doctrine\UserManager;
use Chaplean\Bundle\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContext;
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
    protected $service_container;

    /**
     * @param RouterInterface $router
     * @param SecurityContext $security
     * @param mixed           $service_container
     */
    public function __construct(RouterInterface $router, SecurityContext $security, $service_container)
    {
        $this->router = $router;
        $this->security = $security;
        $this->service_container = $service_container;
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

        // reset token
        if ($user->isEnabled() && $user->getConfirmationToken() != null) {
            /** @var UserManager $userManager */
            $userManager = $this->service_container->get('chaplean_user.user_manager');

            $userManager->cleanUser($user);
            $userManager->updateUser($user, true);
        }

        if ($request->isXmlHttpRequest()) {
            $result = array('success' => true, 'message' => $request->headers->get('referer'));
            $response = new JsonResponse($result);

            return $response;
        } else {
            // redirect to referer page
            $referer = $request->getSession()->get('_security.main.target_path', null);

            if (empty($referer)) {
                $referer = $this->router->generate('opiiec_front_homepage');
            }
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
        // redirect to login page with error
        $messageError = str_replace('.', '', str_replace(' ', '_', strtolower($exception->getMessage())));
        $translator = $this->service_container->get('translator');
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
