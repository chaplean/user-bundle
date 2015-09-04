<?php
/**
 * UserTest.php.
 *
 * @author    Tom - Chaplean <tom@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     1.0.0
 */

namespace Chaplean\Bundle\UserBundle\Tests\Entity;

use Chaplean\Bundle\UnitBundle\Test\LogicalTest;
use Chaplean\Bundle\UserBundle\Entity\User;
use Chaplean\Bundle\UserBundle\Model\AbstractUser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTest extends LogicalTest
{
    public function testEmail()
    {
        $user = $this->getUser();
        $this->assertNull($user->getEmail());

        $user->setEmail('john@chaplean.com');
        $this->assertEquals('john@chaplean.com', $user->getEmail());
    }

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

    public function testIsPasswordRequestAtCleared()
    {
        $user = $this->getUser();
        $passwordRequestedAt = new \DateTime('-10 seconds');

        $user->setPasswordRequestedAt($passwordRequestedAt);
        $user->setPasswordRequestedAt(null);

        $this->assertTrue($user->isPasswordRequestNonExpired(15));
        $this->assertTrue($user->isPasswordRequestNonExpired(5));
    }

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

    public function testFalseHasRole()
    {
        $user = $this->getUser();
        $newrole = 'ROLE_X';
        $this->assertFalse($user->hasRole($newrole));
        $user->addRole($newrole);
        $this->assertTrue($user->hasRole($newrole));
    }

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

class DummyUser extends AbstractUser
{
    /**/
}