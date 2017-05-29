<?php

namespace Tests\Chaplean\Bundle\UserBundle\Model;

use Chaplean\Bundle\UnitBundle\Test\LogicalTestCase;
use Chaplean\Bundle\UserBundle\Doctrine\User;

/**
 * UserTest.php.
 *
 * @author    Tom - Chaplean <tom@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     1.0.0
 */
class UserTest extends LogicalTestCase
{
    /**
     * @covers \Chaplean\Bundle\UserBundle\Model\AbstractUser::getEmail
     *
     * @return void
     */
    public function testEmail()
    {
        $user = $this->getUser();
        $this->assertNull($user->getEmail());

        $user->setEmail('john@chaplean.com');
        $this->assertEquals('john@chaplean.com', $user->getEmail());
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Model\AbstractUser::setPasswordRequestedAt
     * @covers \Chaplean\Bundle\UserBundle\Model\AbstractUser::isPasswordRequestNonExpired
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
     * @covers \Chaplean\Bundle\UserBundle\Model\AbstractUser::hasRole
     * @covers \Chaplean\Bundle\UserBundle\Model\AbstractUser::addRole
     *
     * @return void
     */
    public function testTrueHasRole()
    {
        $user = $this->getUser();
        $defaultrole = User::ROLE_DEFAULT;
        $newrole = 'ROLE_X';
        $this->assertTrue($user->hasRole($defaultrole));
        $user->addRole($defaultrole);
        $this->assertTrue($user->hasRole($defaultrole));
        $user->addRole($newrole);
        $this->assertTrue($user->hasRole($newrole));
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Model\AbstractUser::hasRole
     * @covers \Chaplean\Bundle\UserBundle\Model\AbstractUser::addRole
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
     * @covers \Chaplean\Bundle\UserBundle\Model\AbstractUser::hasRole
     * @covers \Chaplean\Bundle\UserBundle\Model\AbstractUser::addRole
     *
     * @return void
     */
    public function testpasswordSaltElevenChars()
    {
        $user = $this->getUser();

        $this->assertGreaterThan(11, strlen($user->getSalt()));
    }

    /**
     * @return User
     */
    protected function getUser()
    {
        return $this->getMockForAbstractClass('Chaplean\Bundle\UserBundle\Doctrine\User');
    }
}
