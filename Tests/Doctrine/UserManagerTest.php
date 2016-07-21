<?php

namespace Tests\Chaplean\Bundle\UserBundle\Doctrine;

use Chaplean\Bundle\UnitBundle\Test\LogicalTestCase;
use Chaplean\Bundle\UserBundle\Model\AbstractUser;
use Chaplean\Bundle\UserBundle\Doctrine\UserManager;

/**
 * UserManagerTest (cf FOSUser Tests)
 *
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     1.0.0
 */
class UserManagerTest extends LogicalTestCase
{
    const USER_CLASS = 'Tests\Chaplean\Bundle\UserBundle\Doctrine\DummyUser';

    /** @var UserManager */
    protected $userManager;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $em;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $repository;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $ef = $this->getMockBuilder('Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface')->getMock();
        $class = $this->getMockBuilder('Doctrine\Common\Persistence\Mapping\ClassMetadata')->getMock();
        $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManagerInterface')->getMock();
        $this->repository = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectRepository')->getMock();

        $this->em->expects($this->any())
            ->method('getRepository')
            ->with($this->equalTo(static::USER_CLASS))
            ->will($this->returnValue($this->repository));
        $this->em->expects($this->any())
            ->method('getClassMetadata')
            ->with($this->equalTo(static::USER_CLASS))
            ->will($this->returnValue($class));
        $class->expects($this->any())
            ->method('getName')
            ->will($this->returnValue(static::USER_CLASS));

        $this->userManager = $this->createUserManager($ef, $this->em, static::USER_CLASS);
    }

    /**
     * @return void
     */
    public function testDeleteUser()
    {
        $user = $this->getUser();
        $this->em->expects($this->once())->method('remove')->with($this->equalTo($user));
        $this->em->expects($this->once())->method('flush');

        $this->userManager->deleteUser($user);
    }

    /**
     * @return void
     */
    public function testGetClass()
    {
        $this->assertEquals(static::USER_CLASS, $this->userManager->getClass());
    }

    /**
     * @return void
     */
    public function testFindUserBy()
    {
        $crit = array('foo' => 'bar');
        $this->repository->expects($this->once())->method('findOneBy')->with($this->equalTo($crit))->will($this->returnValue(array()));

        $this->userManager->findUserBy($crit);
    }

    /**
     * @return void
     */
    public function testFindUsers()
    {
        $this->repository->expects($this->once())->method('findAll')->will($this->returnValue(array()));

        $this->userManager->findUsers();
    }

    /**
     * @return void
     */
    public function testUpdateUser()
    {
        $user = $this->getUser();
        $this->em->expects($this->once())->method('persist')->with($this->equalTo($user));
        $this->em->expects($this->once())->method('flush');

        $this->userManager->updateUser($user);
    }

    /**
     * @param mixed $encoderFactory
     * @param mixed $em
     * @param mixed $userClass
     *
     * @return UserManager
     */
    protected function createUserManager($encoderFactory, $em, $userClass)
    {
        return new UserManager($encoderFactory, $em, $userClass);
    }

    /**
     * @return mixed
     */
    protected function getUser()
    {
        $userClass = static::USER_CLASS;

        return new $userClass();
    }
}

/**
 * Class DummyUser.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     1.0.0
 */
class DummyUser extends AbstractUser
{
    /**/
}
