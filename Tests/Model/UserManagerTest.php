<?php

namespace Tests\Chaplean\Bundle\UserBundle\Model;

use Chaplean\Bundle\UnitBundle\Test\FunctionalTestCase;
use Chaplean\Bundle\UserBundle\Doctrine\User;
use Chaplean\Bundle\UserBundle\Model\AbstractUserManager;

/**
 * Class UserManagerTest.
 *
 * @package Tests\Chaplean\Bundle\UserBundle\Model
 * @author    Tom - Chaplean <tom@chaplean.coop>
 * @copyright 2014 - 2016 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class UserManagerTest extends FunctionalTestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|AbstractUserManager
     */
    private $manager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $encoderFactory;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->encoderFactory = $this->getMockEncoderFactory();

        $this->manager = $this->getUserManager(
            [
            $this->encoderFactory
            ]
        );
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Model\AbstractUserManager::updatePassword
     *
     * @return void
     */
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
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Model\AbstractUserManager::findUserByEmail
     *
     * @return void
     */
    public function testFindUserByEmail()
    {
        $this->manager->expects($this->once())
            ->method('findUserBy')
            ->with($this->equalTo(['email' => 'jack@email.org']));

        $this->manager->findUserByEmail('jack@email.org');
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Model\AbstractUserManager::findUserByEmail
     *
     * @return void
     */
    public function testFindUserByEmailLowercasesTheEmail()
    {
        $this->manager->expects($this->once())
            ->method('findUserBy')
            ->with($this->equalTo(['email' => 'jack@email.org']));

        $this->manager->findUserByEmail('JaCk@EmAiL.oRg');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockEncoderFactory()
    {
        return $this->getMockBuilder('Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface')->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockPasswordEncoder()
    {
        return $this->getMockBuilder('Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface')->getMock();
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
