<?php

namespace Tests\Chaplean\Bundle\UserBundle\Utility;

use Chaplean\Bundle\UnitBundle\Test\LogicalTestCase;

/**
 * Class RegistrationTest.
 *
 * @package Tests\Chaplean\Bundle\UserBundle\Utility
 * @author    Matthias - Chaplean <matthias@chaplean.com>
 * @copyright 2014 - 2016 Chaplean (http://www.chaplean.com)
 * @since     4.0.0
 */
class RegistrationTest extends LogicalTestCase
{
    /**
     * @return void
     */
    public function testSendRegistrationMailForUser()
    {
        $user = $this->getReference('user-with-pending-reset-password-token');
        $registrationUtility = $this->getContainer()->get('chaplean_user.registration');
        $registrationUtility->sendRegistrationMailForUser($user);

        $messages = $this->readMessages();
        $this->assertCount(1, $messages);
    }

    /**
     * @return void
     */
    public function testSendResettingMailForUser()
    {
        $user = $this->getReference('user-with-pending-reset-password-token');
        $registrationUtility = $this->getContainer()->get('chaplean_user.registration');
        $registrationUtility->sendResettingMailForUser($user);

        $messages = $this->readMessages();
        $this->assertCount(1, $messages);
    }
}
