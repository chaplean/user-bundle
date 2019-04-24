<?php

namespace Tests\Chaplean\Bundle\UserBundle\Utility;

use Chaplean\Bundle\UnitBundle\Test\FunctionalTestCase;
use Chaplean\Bundle\UserBundle\Email\UserPasswordEmail;
use Chaplean\Bundle\UserBundle\Utility\RegistrationUtility;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

/**
 * Class RegistrationUtilityTest.
 *
 * @package Tests\Chaplean\Bundle\UserBundle\Utility
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2016 Chaplean (https://www.chaplean.coopn.coop)
 * @since     4.0.0
 */
class RegistrationUtilityTest extends FunctionalTestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @covers \Chaplean\Bundle\UserBundle\Utility\RegistrationUtility::sendRegistrationMailForUser()
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

        $registrationUtility = new RegistrationUtility(
            $this->getContainer()->getParameter('chaplean_user'),
            $mailer,
            $this->getContainer()->get(UserPasswordEmail::class)
        );
        $registrationUtility->sendRegistrationMailForUser($user);
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Utility\RegistrationUtility::sendResettingMailForUser
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
        $registrationUtility = new RegistrationUtility(
            $this->getContainer()->getParameter('chaplean_user'),
            $mailer,
            $this->getContainer()->get(UserPasswordEmail::class)
        );
        $registrationUtility->sendResettingMailForUser($user);
    }
}
