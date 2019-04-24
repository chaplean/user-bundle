<?php

namespace Tests\Chaplean\Bundle\UserBundle\Security\Handler;

use Chaplean\Bundle\UnitBundle\Test\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class ExceptionHandlerTest.
 *
 * @package   Tests\Chaplean\Bundle\UserBundle\SecurityUtilityTest\Handler
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coopn.coop)
 */
class ExceptionHandlerTest extends FunctionalTestCase
{
    /**
     * @covers \Chaplean\Bundle\UserBundle\Security\Handler\ExceptionHandler::onException()
     *
     * @return void
     * @throws \Exception
     */
    public function testNotApiUri()
    {
        $dispatcher = $this->getContainer()->get('event_dispatcher');

        $request = Request::create('/');
        $responseForExceptionEvent = new GetResponseForExceptionEvent(
            $this->getContainer()->get('kernel'),
            $request,
            'GET',
            new AccessDeniedHttpException('/')
        );

        $dispatcher->dispatch(KernelEvents::EXCEPTION, $responseForExceptionEvent);

        $this->assertEquals(Response::HTTP_OK, $responseForExceptionEvent->getResponse()->getStatusCode());
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Security\Handler\ExceptionHandler::onException()
     *
     * @return void
     * @throws \Exception
     */
    public function testApiUri()
    {
        $dispatcher = $this->getContainer()->get('event_dispatcher');

        $request = Request::create('/api/all');
        $responseForExceptionEvent = new GetResponseForExceptionEvent(
            $this->getContainer()->get('kernel'),
            $request,
            'GET',
            new AccessDeniedHttpException('/api/all')
        );

        $dispatcher->dispatch(KernelEvents::EXCEPTION, $responseForExceptionEvent);

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $responseForExceptionEvent->getResponse()->getStatusCode());
    }
}
