<?php

namespace Chaplean\Bundle\UserBundle\Doctrine;

use Chaplean\Bundle\UserBundle\Model\AbstractUserManager;
use Chaplean\Bundle\UserBundle\Model\UserInterface;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * UserManager
 *
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     1.0.0
 */
class UserManager extends AbstractUserManager
{
    private $em;
    private $repository;
    private $class;

    /**
     * Constructor.
     *
     * @param EncoderFactoryInterface $encoderFactory
     * @param Registry                $registry
     * @param string                  $class
     */
    public function __construct(EncoderFactoryInterface $encoderFactory, Registry $registry, $class)
    {
        parent::__construct($encoderFactory);

        $this->em = $registry->getManager();
        $this->repository = $this->em->getRepository($class);

        $metadata = $this->em->getClassMetadata($class);
        $this->class = $metadata->getName();
    }

    /**
     * @param UserInterface $user
     *
     * @return void
     */
    public function deleteUser(UserInterface $user)
    {
        $this->em->remove($user);
        $this->em->flush();
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param array $criteria
     *
     * @return object
     */
    public function findUserBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * @param array $criteria
     *
     * @return object
     */
    public function findOneBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * @return array
     */
    public function findUsers()
    {
        return $this->repository->findAll();
    }

    /**
     * @return array
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

    /**
     * @param UserInterface $user
     *
     * @return void
     */
    public function reloadUser(UserInterface $user)
    {
        $this->em->refresh($user);
    }

    /**
     * Active a user.
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function activeUser(UserInterface $user)
    {
        $user->setConfirmationToken(null);
        $user->setEnabled(true);
    }

    /**
     * Clean token and date password request for a user.
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function cleanUser(UserInterface $user)
    {
        $user->setConfirmationToken(null);
        $user->setPasswordRequestedAt(null);
    }

    /**
     * Updates a user.
     *
     * @param UserInterface $user
     * @param boolean       $andFlush Whether to flush the changes (default true)
     *
     * @return void
     */
    public function updateUser(UserInterface $user, $andFlush = true)
    {
        $this->updatePassword($user);

        $this->em->persist($user);

        if ($andFlush) {
            $this->em->flush();
        }
    }

    /**
     * Remove a user.
     *
     * @param UserInterface $user
     *
     * @return void
     * @deprecated use deleteUser()
     */
    public function removeUser(UserInterface $user)
    {
        $this->deleteUser($user);
    }
}
