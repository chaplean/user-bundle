<?php

namespace Chaplean\Bundle\UserBundle\Model;

use FOS\UserBundle\Model\User;
use FOS\UserBundle\Model\UserInterface as FOSUserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class AbstractUserManager.
 *
 * @package   Chaplean\Bundle\UserBundle\Model
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.coop)
 * @since     2.0.0
 */
abstract class AbstractUserManager implements UserManagerInterface, UserProviderInterface
{
    /**
     * @var EncoderFactoryInterface
     */
    protected $encoderFactory;

    /**
     * Constructor.
     *
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
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
        return $this->findUserBy(array('email' => strtolower($email)));
    }

    /**
     * Finds a user by username
     *
     * @param string $username
     *
     * @return FOSUserInterface
     */
    public function findUserByUsername($username)
    {
        return $this->findUserBy(array('username' => strtolower($username)));
    }

    /**
     * Finds a user either by email, or username
     *
     * @param string $usernameOrEmail
     *
     * @return FOSUserInterface
     */
    public function findUserByUsernameOrEmail($usernameOrEmail)
    {
        $usernameOrEmail = strtolower($usernameOrEmail);

        if (filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL)) {
            $user = $this->findUserByEmail($usernameOrEmail);
        } else {
            $user = $this->findUserByUsername($usernameOrEmail);
        }

        return $user;
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
        return $this->findUserBy(array('confirmationToken' => $token));
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
     * @deprecated Use FOS\UserBundle\Security\UserProvider instead
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

        $refreshedUser = $this->findUserBy(array('id' => $user->getId()));
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
     * @deprecated Use FOS\UserBundle\Security\UserProvider instead
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
     * @deprecated Use FOS\UserBundle\Security\UserProvider instead
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
}
