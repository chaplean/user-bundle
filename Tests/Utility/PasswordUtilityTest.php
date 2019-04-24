<?php

namespace Tests\Chaplean\Bundle\UserBundle\Utility;

use Chaplean\Bundle\UnitBundle\Test\FunctionalTestCase;

/**
 * Class PasswordUtilityTest.
 *
 * @package Tests\Chaplean\Bundle\UserBundle\Utility
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2016 Chaplean (https://www.chaplean.coopn.coop)
 * @since     4.0.0
 */
class PasswordUtilityTest extends FunctionalTestCase
{
    /**
     * @covers \Chaplean\Bundle\UserBundle\Utility\PasswordUtility::isTokenValid
     *
     * @return void
     * @throws \Exception
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
     * @throws \Exception
     */
    public function testIsTokenValidInvalidToken()
    {
        $passwordUtility = $this->getContainer()->get('chaplean_user.password_utility');
        $this->assertFalse($passwordUtility->isTokenValid('00'));
    }
}
