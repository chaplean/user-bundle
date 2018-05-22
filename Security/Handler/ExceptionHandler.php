<?php

namespace Chaplean\Bundle\UserBundle\Security\Handler;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class ExceptionHandler.
 *
 * @package   Chaplean\Bundle\UserBundle\Security\Handler
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (http://www.chaplean.coop)
 */
class ExceptionHandler
{
    /**
     * @param GetResponseForExceptionEvent $event
     *
     * @return void
     */
    public function onException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $request = $event->getRequest();

        if ($exception instanceof AccessDeniedHttpException && strpos($request->getUri(), '/api/')) {
            $event->setResponse(new JsonResponse(['error' => '401 Unauthorized'], 401));
        }
    }
}
