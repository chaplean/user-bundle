<?php

namespace Chaplean\Bundle\UserBundle\DataFixtures\Liip\DefaultData;

use Chaplean\Bundle\UnitBundle\Utility\AbstractFixture;
use Chaplean\Bundle\UserBundle\Entity\DummyUser;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadPostData.php.
 *
 * @package   Chaplean\Bundle\UserBundle\DataFixtures\Liip\DefaultData
 * @author    Matthias - Chaplean <matthias@chaplean.com>
 * @copyright 2014 - 2016 Chaplean (http://www.chaplean.com)
 * @since     4.0.0
 */
class LoadUserData extends AbstractFixture
{
    /**
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $user = new DummyUser();
        $user->setEmail('user-1@test.com');
        $user->setFirstname('test');
        $user->setLastname('test');
        $user->setPassword('');
        $user->setDateAdd(new \DateTime());
        $this->persist($user, $manager);
        $this->setReference('user-1', $user);

        $user = new DummyUser();
        $user->setEmail('user-with-pending-reset-password-token@test.com');
        $user->setFirstname('test');
        $user->setLastname('test');
        $user->setPassword('');
        $user->setDateAdd(new \DateTime());
        $user->setConfirmationToken('42');
        $this->persist($user, $manager);
        $this->setReference('user-with-pending-reset-password-token', $user);

        $manager->flush();
    }
}
