<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Chaplean\Bundle\UserBundle\Tests\Model;

use Chaplean\Bundle\UserBundle\Doctrine\User;

class UserManagerTest extends \PHPUnit_Framework_TestCase
{
    private $manager;
    private $encoderFactory;

    protected function setUp()
    {
        $this->encoderFactory = $this->getMockEncoderFactory();

        $this->manager = $this->getUserManager(array(
            $this->encoderFactory
        ));
    }

    public function testUpdatePassword()
    {
        $encoder = $this->getMockPasswordEncoder();
        /** @var User $user */
        $user = $this->getUser();
        $user->setPlainPassword('password');

        $this->encoderFactory->expects($this->once())
            ->method('getEncoder')
            ->will($this->returnValue($encoder));

        $encoder->expects($this->once())
            ->method('encodePassword')
            ->with('password', $user->getSalt())
            ->will($this->returnValue('encodedPassword'));

        $this->manager->updatePassword($user);
        $this->assertEquals('encodedPassword', $user->getPassword(), '->updatePassword() sets encoded password');
        $this->assertNull($user->getPlainPassword(), '->updatePassword() erases credentials');
    }

    public function testFindUserByEmail()
    {
        $this->manager->expects($this->once())
            ->method('findUserBy')
            ->with($this->equalTo(array('email' => 'jack@email.org')));

        $this->manager->findUserByEmail('jack@email.org');
    }

    public function testFindUserByEmailLowercasesTheEmail()
    {
        $this->manager->expects($this->once())
            ->method('findUserBy')
            ->with($this->equalTo(array('email' => 'jack@email.org')));

        $this->manager->findUserByEmail('JaCk@EmAiL.oRg');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockEncoderFactory()
    {
        return $this->getMock('Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockPasswordEncoder()
    {
        return $this->getMock('Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getUser()
    {
        return $this->getMockBuilder('Chaplean\Bundle\UserBundle\Doctrine\User')
            ->getMockForAbstractClass();
    }

    /**
     * @param array $args
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getUserManager(array $args)
    {
        return $this->getMockBuilder('Chaplean\Bundle\UserBundle\Model\AbstractUserManager')
            ->setConstructorArgs($args)
            ->getMockForAbstractClass();
    }
}