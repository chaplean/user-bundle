<?php

namespace Chaplean\Bundle\UserBundle\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use FOS\UserBundle\Model\User;
use FOS\UserBundle\Model\UserInterface as FOSUserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class UserManager.
 *
 * @package   Chaplean\Bundle\UserBundle\Model
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.coop)
 * @since     2.0.0
 */
class UserManager implements UserManagerInterface, UserProviderInterface
{
    /**
     * @var EncoderFactoryInterface
     */
    protected $encoderFactory;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @var User
     */
    protected $class;

    /**
     * Constructor.
     *
     * @param EncoderFactoryInterface $encoderFactory
     * @param RegistryInterface       $registry
     * @param string                  $class
     */
    public function __construct(EncoderFactoryInterface $encoderFactory, RegistryInterface $registry, $class)
    {
        $this->encoderFactory = $encoderFactory;

        $this->em = $registry->getManager();
        $this->repository = $registry->getRepository($class);

        $metadata = $this->em->getClassMetadata($class);
        $this->class = $metadata->getName();
    }

    /**
     * Returns an empty user instance
     *
     * @return FOSUserInterface
     */
    public function createUser()
    {
        $class = $this->getClass();
        $user = new $class;

        return $user;
    }

    /**
     * Finds a user by email
     *
     * @param string $email
     *
     * @return FOSUserInterface
     */
    public function findUserByEmail($email)
    {
        return $this->findUserBy(['email' => strtolower($email)]);
    }

    /**
     * Finds a user either by confirmation token
     *
     * @param string $token
     *
     * @return FOSUserInterface
     */
    public function findUserByConfirmationToken($token)
    {
        return $this->findUserBy(['confirmationToken' => $token]);
    }

    /**
     * Refreshed a user by User Instance
     *
     * Throws UnsupportedUserException if a User Instance is given which is not
     * managed by this UserManager (so another Manager could try managing it)
     *
     * It is strongly discouraged to use this method manually as it bypasses
     * all ACL checks.
     *
     * @deprecated Use FOS\UserBundle\SecurityUtility\UserProvider instead
     *
     * @param SecurityUserInterface $user
     *
     * @return FOSUserInterface
     */
    public function refreshUser(SecurityUserInterface $user)
    {
        trigger_error('Using the UserManager as user provider is deprecated. Use FOS\UserBundle\Security\UserProvider instead.', E_USER_DEPRECATED);

        $class = $this->getClass();
        if (!$user instanceof $class) {
            throw new UnsupportedUserException('Account is not supported.');
        }
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Expected an instance of FOS\UserBundle\Model\User, but got "%s".', get_class($user)));
        }

        $refreshedUser = $this->findUserBy(['id' => $user->getId()]);
        if (null === $refreshedUser) {
            throw new UsernameNotFoundException(sprintf('User with ID "%d" could not be reloaded.', $user->getId()));
        }

        return $refreshedUser;
    }

    /**
     * Loads a user by username
     *
     * It is strongly discouraged to call this method manually as it bypasses
     * all ACL checks.
     *
     * @deprecated Use FOS\UserBundle\SecurityUtility\UserProvider instead
     *
     * @param string $username
     *
     * @return FOSUserInterface
     */
    public function loadUserByUsername($username)
    {
        trigger_error('Using the UserManager as user provider is deprecated. Use FOS\UserBundle\Security\UserProvider instead.', E_USER_DEPRECATED);

        $user = $this->findUserByUsername($username);

        if (is_array($user)) {
            $user = $user[0];
        }

        if (!$user) {
            throw new UsernameNotFoundException(sprintf('No user with name "%s" was found.', $username));
        }

        return $user;
    }

    /**
     * @param string $email
     *
     * @return UserInterface
     */
    public function loadUserByEmail($email)
    {
        $user = $this->findUserByEmail($email);

        if (is_array($user)) {
            $user = $user[0];
        }

        if (!$user) {
            throw new UsernameNotFoundException(sprintf('No user with email "%s" was found.', $email));
        }

        return $user;
    }

    /**
     * @param \FOS\UserBundle\Model\UserInterface $user
     *
     * @return void
     */
    public function updatePassword(FOSUserInterface $user)
    {
        if (0 !== strlen($password = $user->getPlainPassword())) {
            $encoder = $this->getEncoder($user);
            $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
        }
    }

    /**
     * @param FOSUserInterface $user
     *
     * @return PasswordEncoderInterface
     */
    protected function getEncoder(FOSUserInterface $user)
    {
        return $this->encoderFactory->getEncoder($user);
    }

    /**
     * @deprecated Use FOS\UserBundle\SecurityUtility\UserProvider instead
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        trigger_error('Using the UserManager as user provider is deprecated. Use FOS\UserBundle\Security\UserProvider instead.', E_USER_DEPRECATED);

        return $class === $this->getClass();
    }

    /**
     * @param FOSUserInterface $user
     *
     * @return void
     */
    public function deleteUser(FOSUserInterface $user)
    {
        $this->em->remove($user);
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
     * @param FOSUserInterface $user
     *
     * @return void
     */
    public function reloadUser(FOSUserInterface $user)
    {
        $this->em->refresh($user);
    }

    /**
     * Active a user.
     *
     * @param FOSUserInterface $user
     *
     * @return void
     */
    public function activeUser(FOSUserInterface $user)
    {
        $user->setConfirmationToken(null);
        $user->setEnabled(true);
    }

    /**
     * Clean token and date password request for a user.
     *
     * @param FOSUserInterface $user
     *
     * @return void
     */
    public function cleanUser(FOSUserInterface $user)
    {
        $user->setConfirmationToken(null);
        $user->setPasswordRequestedAt(null);
    }

    /**
     * Updates a user.
     *
     * @param FOSUserInterface $user
     *
     * @return void
     */
    public function updateUser(FOSUserInterface $user)
    {
        $this->updatePassword($user);

        $this->em->persist($user);

        $user->eraseCredentials();
    }

    /**
     * Remove a user.
     *
     * @param FOSUserInterface $user
     *
     * @return void
     * @deprecated use deleteUser()
     */
    public function removeUser(FOSUserInterface $user)
    {
        $this->deleteUser($user);
    }

    /**
     * Find a user by its username.
     *
     * @param string $username
     *
     * @return FOSUserInterface or null if user does not exist
     */
    public function findUserByUsername($username)
    {
        return $this->findUserByEmail($username);
    }

    /**
     * Finds a user by its username or email.
     *
     * @param string $usernameOrEmail
     *
     * @return FOSUserInterface or null if user does not exist
     */
    public function findUserByUsernameOrEmail($usernameOrEmail)
    {
        return $this->findUserByEmail($usernameOrEmail);
    }

    /**
     * Updates the canonical username and email fields for a user.
     *
     * @param FOSUserInterface $user
     */
    public function updateCanonicalFields(FOSUserInterface $user)
    {
    }
}
