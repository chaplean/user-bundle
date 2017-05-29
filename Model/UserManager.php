<?php
/**
 * UserManager
 *
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     1.0.0
 */

namespace Chaplean\Bundle\UserBundle\Model;

use FOS\UserBundle\Model\UserInterface as FOSUserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Util\CanonicalizerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

abstract class UserManager implements UserManagerInterface, UserProviderInterface
{
    /**
     * @var EncoderFactoryInterface
     */
    protected $encoderFactory;

    /**
     * @var CanonicalizerInterface
     */
    protected $usernameCanonicalizer;

    /**
     * @var CanonicalizerInterface
     */
    protected $emailCanonicalizer;

    /**
     * Constructor.
     *
     * @param EncoderFactoryInterface $encoderFactory
     * @param CanonicalizerInterface  $usernameCanonicalizer
     * @param CanonicalizerInterface  $emailCanonicalizer
     */
    public function __construct(EncoderFactoryInterface $encoderFactory, CanonicalizerInterface $usernameCanonicalizer, CanonicalizerInterface $emailCanonicalizer)
    {
        $this->encoderFactory = $encoderFactory;
        $this->usernameCanonicalizer = $usernameCanonicalizer;
        $this->emailCanonicalizer = $emailCanonicalizer;
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
     * {@inheritDoc}
     */
    public function updatePassword(FOSUserInterface $user)
    {
        if (0 !== strlen($password = $user->getPlainPassword())) {
            $encoder = $this->getEncoder($user);
            $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
            $user->eraseCredentials();
        }
    }

    /**
     * @param FOSUserInterface $user
     *
     * @return PasswordEncoderInterface
     */
    protected function getEncoder(UserInterface $user)
    {
        return $this->encoderFactory->getEncoder($user);
    }

    /**
     * {@inheritDoc}
     * @deprecated Use FOS\UserBundle\Security\UserProvider instead
     */
    public function supportsClass($class)
    {
        trigger_error('Using the UserManager as user provider is deprecated. Use FOS\UserBundle\Security\UserProvider instead.', E_USER_DEPRECATED);

        return $class === $this->getClass();
    }
}
