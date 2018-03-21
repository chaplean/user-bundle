<?php

namespace Tests\Chaplean\Bundle\UserBundle\Utility;

use Chaplean\Bundle\UnitBundle\Test\FunctionalTestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

/**
 * Class RegistrationTest.
 *
 * @package Tests\Chaplean\Bundle\UserBundle\Utility
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2016 Chaplean (http://www.chaplean.coop)
 * @since     4.0.0
 */
class RegistrationTest extends FunctionalTestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @covers \Chaplean\Bundle\UserBundle\Utility\Registration::sendResettingMailForUser
     *
     * @return void
     * @throws \Exception
     */
    public function testSendRegistrationMailForUser()
    {
        $mailer = \Mockery::mock(\Swift_Mailer::class);
        $this->getContainer()->set('swiftmailer.mailer', $mailer);

        $mailer->shouldReceive('send')->once()->andReturnNull();

        $user = $this->getReference('user-with-pending-reset-password-token');
        $registrationUtility = $this->getContainer()->get('chaplean_user.registration');
        $registrationUtility->sendRegistrationMailForUser($user);
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Utility\Registration::sendResettingMailForUser
     *
     * @return void
     * @throws \Exception
     */
    public function testSendResettingMailForUser()
    {
        $mailer = \Mockery::mock(\Swift_Mailer::class);
        $this->getContainer()->set('swiftmailer.mailer', $mailer);

        $mailer->shouldReceive('send')->once()->andReturnNull();

        $user = $this->getReference('user-with-pending-reset-password-token');
        $registrationUtility = $this->getContainer()->get('chaplean_user.registration');
        $registrationUtility->sendResettingMailForUser($user);
    }
}
