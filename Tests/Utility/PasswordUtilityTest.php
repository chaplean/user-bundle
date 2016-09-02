<?php

namespace Tests\Chaplean\Bundle\UserBundle\Utility;

use Chaplean\Bundle\UnitBundle\Test\LogicalTestCase;

/**
 * Class PasswordUtilityTest.
 *
 * @package Tests\Chaplean\Bundle\UserBundle\Utility
 * @author    Matthias - Chaplean <matthias@chaplean.com>
 * @copyright 2014 - 2016 Chaplean (http://www.chaplean.com)
 * @since     4.0.0
 */
class PasswordUtilityTest extends LogicalTestCase
{
    /**
     * @return void
     */
    public function testIsTokenValidValidToken()
    {
        $passwordUtility = $this->getContainer()->get('chaplean_user.password_utility');
        $this->assertTrue($passwordUtility->isTokenValid('42'));
    }

    /**
     * @return void
     */
    public function testIsTokenValidInvalidToken()
    {
        $passwordUtility = $this->getContainer()->get('chaplean_user.password_utility');
        $this->assertFalse($passwordUtility->isTokenValid('00'));
    }
}