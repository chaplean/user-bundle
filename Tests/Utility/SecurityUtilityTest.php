<?php

namespace Chaplean\Bundle\UserBundle\Tests\Utility;

use Chaplean\Bundle\UserBundle\Utility\SecurityUtility;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityUtilityTest.
 *
 * @package   Chaplean\Bundle\UserBundle\Tests\Utility
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (http://www.chaplean.coop)
 */
class SecurityUtilityTest extends MockeryTestCase
{
    /**
     * @covers Chaplean\Bundle\UserBundle\Utility\SecurityUtility::__construct()
     * @covers Chaplean\Bundle\UserBundle\Utility\SecurityUtility::getParametersLogin()
     *
     * @return void
     */
    public function testGetParametersLogin()
    {
        $authenticationUtils = \Mockery::mock(AuthenticationUtils::class);
        $securityUtility = new SecurityUtility($authenticationUtils);

        $authenticationUtils->shouldReceive('getLastUsername')->once()->andReturn('username');
        $authenticationUtils->shouldReceive('getLastAuthenticationError')->once()->andReturn('error');

        $expected = ['last_username' => 'username', 'error' => 'error'];
        $actual = $securityUtility->getParametersLogin();

        $this->assertEquals($expected, $actual);
    }
}
