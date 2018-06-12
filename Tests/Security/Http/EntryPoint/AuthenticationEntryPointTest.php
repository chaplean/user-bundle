<?php

namespace Tests\Chaplean\Bundle\UserBundle\Security\Http\EntryPoint;

use Chaplean\Bundle\UserBundle\Security\Http\EntryPoint\AuthenticationEntryPoint;
use Chaplean\Bundle\UnitBundle\Test\FunctionalTestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;
use Symfony\Component\Security\Http\HttpUtils;

/**
 * Class AuthenticationEntryPointTest.
 *
 * @package   Tests\Chaplean\Bundle\UserBundle\SecurityUtilityTest\Http\EntryPoint
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (http://www.chaplean.coop)
 */
class AuthenticationEntryPointTest extends FunctionalTestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @covers \Chaplean\Bundle\UserBundle\Security\Http\EntryPoint\AuthenticationEntryPoint::__construct
     * @covers \Chaplean\Bundle\UserBundle\Security\Http\EntryPoint\AuthenticationEntryPoint::start()
     *
     * @return void
     */
    public function testStartOnFrontRoute()
    {
        $kernel = \Mockery::mock(HttpKernelInterface::class);
        $httpUtils = \Mockery::mock(HttpUtils::class);
        $request = Request::create('/');

        $authenticationEntryPoint = new AuthenticationEntryPoint(
            $kernel,
            $httpUtils,
            '',
            false
        );

        $httpUtils->shouldReceive('createRedirectResponse')->once()->andReturn(new Response());

        $authenticationEntryPoint->start($request, new InsufficientAuthenticationException());
    }
    /**
     * @covers \Chaplean\Bundle\UserBundle\Security\Http\EntryPoint\AuthenticationEntryPoint::__construct
     * @covers \Chaplean\Bundle\UserBundle\Security\Http\EntryPoint\AuthenticationEntryPoint::start()
     *
     * @return void
     */
    public function testStartOnRestRoute()
    {
        $kernel = \Mockery::mock(HttpKernelInterface::class);
        $httpUtils = \Mockery::mock(HttpUtils::class);
        $request = Request::create('/api/all');

        $authenticationEntryPoint = new AuthenticationEntryPoint(
            $kernel,
            $httpUtils,
            '',
            false
        );

        $httpUtils->shouldReceive('createRedirectResponse')->never();

        $response = $authenticationEntryPoint->start($request, new InsufficientAuthenticationException());

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }
}
