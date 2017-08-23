<?php

namespace Tests\Chaplean\Bundle\UserBundle\Entity;

use Chaplean\Bundle\UnitBundle\Test\LogicalTestCase;
use Chaplean\Bundle\UserBundle\Model\AbstractUser;

/**
 * UserTest.php.
 *
 * @author    Tom - Chaplean <tom@chaplean.coop>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class UserTest extends LogicalTestCase
{
    /**
     * @covers \Chaplean\Bundle\UserBundle\Doctrine\User::getEmail
     * @covers \Chaplean\Bundle\UserBundle\Doctrine\User::setEmail
     *
     * @return void
     */
    public function testEmail()
    {
        $user = $this->getUser();
        $this->assertNull($user->getEmail());

        $user->setEmail('john@chaplean.coop');
        $this->assertEquals('john@chaplean.coop', $user->getEmail());
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Doctrine\User::getPasswordRequestedAt
     * @covers \Chaplean\Bundle\UserBundle\Doctrine\User::setPasswordRequestedAt
     * @covers \Chaplean\Bundle\UserBundle\Doctrine\User::isPasswordRequestNonExpired
     * @covers \Chaplean\Bundle\UserBundle\Doctrine\User::setEnabled
     *
     * @return void
     */
    public function testIsPasswordRequestNonExpired()
    {
        $user = $this->getUser();
        $user->setEnabled(true);

        $passwordRequestedAt = new \DateTime('-10 seconds');

        $user->setPasswordRequestedAt($passwordRequestedAt);

        $this->assertSame($passwordRequestedAt, $user->getPasswordRequestedAt());
        $this->assertTrue($user->isPasswordRequestNonExpired(15));
        $this->assertFalse($user->isPasswordRequestNonExpired(5));
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Doctrine\User::setPasswordRequestedAt
     * @covers \Chaplean\Bundle\UserBundle\Doctrine\User::isPasswordRequestNonExpired
     *
     * @return void
     */
    public function testIsPasswordRequestAtCleared()
    {
        $user = $this->getUser();
        $passwordRequestedAt = new \DateTime('-10 seconds');

        $user->setPasswordRequestedAt($passwordRequestedAt);
        $user->setPasswordRequestedAt(null);

        $this->assertTrue($user->isPasswordRequestNonExpired(15));
        $this->assertTrue($user->isPasswordRequestNonExpired(5));
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Doctrine\User::hasRole
     * @covers \Chaplean\Bundle\UserBundle\Doctrine\User::addRole
     *
     * @return void
     */
    public function testTrueHasRole()
    {
        $user = $this->getUser();
        $defaultrole = AbstractUser::ROLE_DEFAULT;
        $newrole = 'ROLE_X';
        $this->assertTrue($user->hasRole($defaultrole));
        $user->addRole($defaultrole);
        $this->assertTrue($user->hasRole($defaultrole));
        $user->addRole($newrole);
        $this->assertTrue($user->hasRole($newrole));
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Doctrine\User::hasRole
     * @covers \Chaplean\Bundle\UserBundle\Doctrine\User::addRole
     *
     * @return void
     */
    public function testFalseHasRole()
    {
        $user = $this->getUser();
        $newrole = 'ROLE_X';
        $this->assertFalse($user->hasRole($newrole));
        $user->addRole($newrole);
        $this->assertTrue($user->hasRole($newrole));
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Doctrine\User::getSalt
     *
     * @return void
     */
    public function testpasswordSaltElevenChars()
    {
        $user = $this->getUser();

        $this->assertGreaterThan(11, strlen($user->getSalt()));
    }

    /**
     * @return DummyUser
     */
    protected function getUser()
    {
        return new DummyUser();
    }
}

/**
 * Class DummyUser.
 *
 * @package   Tests\Chaplean\Bundle\UserBundle\Entity
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class DummyUser extends AbstractUser
{
    /**/
}
