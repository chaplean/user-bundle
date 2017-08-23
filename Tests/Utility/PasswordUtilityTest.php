<?php

namespace Tests\Chaplean\Bundle\UserBundle\Utility;

use Chaplean\Bundle\UnitBundle\Test\LogicalTestCase;

/**
 * Class PasswordUtilityTest.
 *
 * @package Tests\Chaplean\Bundle\UserBundle\Utility
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2016 Chaplean (http://www.chaplean.coop)
 * @since     4.0.0
 */
class PasswordUtilityTest extends LogicalTestCase
{
    /**
     * @covers \Chaplean\Bundle\UserBundle\Utility\PasswordUtility::isTokenValid
     *
     * @return void
     */
    public function testIsTokenValidValidToken()
    {
        $passwordUtility = $this->getContainer()->get('chaplean_user.password_utility');
        $this->assertTrue($passwordUtility->isTokenValid('42'));
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Utility\PasswordUtility::isTokenValid
     *
     * @return void
     */
    public function testIsTokenValidInvalidToken()
    {
        $passwordUtility = $this->getContainer()->get('chaplean_user.password_utility');
        $this->assertFalse($passwordUtility->isTokenValid('00'));
    }
}
