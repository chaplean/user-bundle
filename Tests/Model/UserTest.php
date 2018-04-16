<?php

namespace Tests\Chaplean\Bundle\UserBundle\Model;

use Chaplean\Bundle\UnitBundle\Test\FunctionalTestCase;
use Chaplean\Bundle\UserBundle\Model\User;

/**
 * UserTest.php.
 *
 * @author    Tom - Chaplean <tom@chaplean.coop>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class UserTest extends FunctionalTestCase
{
    /**
     * @covers \Chaplean\Bundle\UserBundle\Model\User::getEmail
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
     * @covers \Chaplean\Bundle\UserBundle\Model\User::setPasswordRequestedAt
     * @covers \Chaplean\Bundle\UserBundle\Model\User::isPasswordRequestNonExpired
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
     * @covers \Chaplean\Bundle\UserBundle\Model\User::hasRole
     * @covers \Chaplean\Bundle\UserBundle\Model\User::addRole
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
     * @covers \Chaplean\Bundle\UserBundle\Model\User::hasRole
     * @covers \Chaplean\Bundle\UserBundle\Model\User::addRole
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
     * @return User
     */
    protected function getUser()
    {
        return $this->getMockForAbstractClass('Chaplean\Bundle\UserBundle\Model\User');
    }
}
