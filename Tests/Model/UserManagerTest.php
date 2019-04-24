<?php

namespace Tests\Chaplean\Bundle\UserBundle\Model;

use Chaplean\Bundle\UnitBundle\Test\FunctionalTestCase;
use Chaplean\Bundle\UserBundle\Model\User;
use Chaplean\Bundle\UserBundle\Model\UserManager;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

/**
 * Class UserManagerTest.
 *
 * @package   Tests\Chaplean\Bundle\UserBundle\Model
 * @author    Tom - Chaplean <tom@chaplean.coop>
 * @copyright 2014 - 2016 Chaplean (https://www.chaplean.coopn.coop)
 * @since     1.0.0
 */
class UserManagerTest extends FunctionalTestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|UserManager
     */
    private $manager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $encoderFactory;

    /**
     * @var RegistryInterface
     */
    private $registry;

    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * @var ObjectRepository
     */
    private $repository;

    /**
     * @var ClassMetadata
     */
    private $classMetadata;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->encoderFactory = $this->getMockEncoderFactory();
        $this->registry = $this->getMockRegistryInterface();
        $this->entityManager = $this->getMockEntityManagerInterface();
        $this->repository = $this->getMockRepository();
        $this->classMetadata = $this->getMockClassMetadata();

        $this->registry->expects($this->once())
            ->method('getManager')
            ->willReturn($this->entityManager);

        $this->registry->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->repository);

        $this->entityManager->expects($this->once())
            ->method('getClassMetadata')
            ->willReturn($this->classMetadata);

        $this->classMetadata->expects($this->once())
            ->method('getName')
            ->willReturn('User');

        $this->manager = new UserManager($this->encoderFactory, $this->registry, User::class);
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Model\UserManager::updatePassword
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
            ->willReturn('encodedPassword');

        $this->manager->updatePassword($user);
        $user->setPassword('encodedPassword');
        $this->assertEquals('encodedPassword', $user->getPassword(), '->updatePassword() sets encoded password');
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Model\UserManager::findUserByEmail
     *
     * @return void
     */
    public function testFindUserByEmail()
    {
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with($this->equalTo(['email' => 'jack@email.org']));

        $this->manager->findUserByEmail('jack@email.org');
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\Model\UserManager::findUserByEmail
     *
     * @return void
     */
    public function testFindUserByEmailLowercasesTheEmail()
    {
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with($this->equalTo(['email' => 'jack@email.org']));

        $this->manager->findUserByEmail('JaCk@EmAiL.oRg');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockEncoderFactory()
    {
        return $this->getMockBuilder(EncoderFactoryInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockRegistryInterface()
    {
        return $this->getMockBuilder(RegistryInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockEntityManagerInterface()
    {
        return $this->getMockBuilder(ObjectManager::class)
            ->getMockForAbstractClass();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockRepository()
    {
        return $this->getMockBuilder(ObjectRepository::class)
            ->getMockForAbstractClass();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockClassMetadata()
    {
        return $this->getMockBuilder(ClassMetadata::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockPasswordEncoder()
    {
        return $this->getMockBuilder(PasswordEncoderInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getUser()
    {
        return $this->getMockBuilder(User::class)
            ->getMockForAbstractClass();
    }
}
