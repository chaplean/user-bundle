<?php

namespace Chaplean\Bundle\UserBundle\Security\Http\EntryPoint;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\FormAuthenticationEntryPoint;

/**
 * Class AuthenticationEntryPoint.
 *
 * @package   Chaplean\Bundle\UserBundle\SecurityUtility\Http\EntryPoint
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (http://www.chaplean.coop)
 */
class AuthenticationEntryPoint extends FormAuthenticationEntryPoint
{
    /**
     * Starts the authentication scheme.
     *
     * @param Request                 $request       The request that resulted in an AuthenticationException
     * @param AuthenticationException $authException The exception that started the authentication process
     *
     * @return Response
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        if ($authException instanceof InsufficientAuthenticationException && strpos($request->getUri(), '/api/') !== false) {
            return new JsonResponse(['error' => '401 Unauthorized'], 401);
        }

        return parent::start($request, $authException);
    }
}
