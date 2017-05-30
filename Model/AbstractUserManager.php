<?php
namespace Chaplean\Bundle\UserBundle\Model;

use Chaplean\Bundle\UserBundle\Doctrine\User;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Model\UserInterface;
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
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
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
     * @return UserInterface
     */
    public function createUser()
    {
        $class = $this->getClass();

        return new $class;
    }

    /**
     * Finds a user by email
     *
     * @param string $email
     *
     * @return UserInterface
     */
    public function findUserByEmail($email)
    {
        return $this->findUserBy(array('email' => strtolower($email)));
    }

    /**
     * Finds a user either by confirmation token
     *
     * @param string $token
     *
     * @return UserInterface
     */
    public function findUserByConfirmationToken($token)
    {
        return $this->findUserBy(array('confirmationToken' => $token));
    }

    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $username The username
     *
     * @return void
     *
     * @deprecated Username is identified by email
     */
    public function loadUserByUsername($username)
    {
        return;
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
     * @return UserInterface
     */
    public function refreshUser(SecurityUserInterface $user)
    {
        trigger_error('Using the UserManager as user provider is deprecated. Use FOS\UserBundle\Security\UserProvider instead.', E_USER_DEPRECATED);

        $class = $this->getClass();
        if (!$user instanceof $class) {
            throw new UnsupportedUserException('Account is not supported.');
        }
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Expected an instance of Chaplean\Bundle\UserBundle\Model\User, but got "%s".', get_class($user)));
        }

        $refreshedUser = $this->findUserBy(array('id' => $user->getId()));
        if (null === $refreshedUser) {
            throw new UsernameNotFoundException(sprintf('User with ID "%d" could not be reloaded.', $user->getId()));
        }

        return $refreshedUser;
    }

    /**
     * Updates a user.
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function updatePassword(UserInterface $user)
    {
        if (0 !== strlen($password = $user->getPlainPassword())) {
            $encoder = $this->getEncoder($user);
            $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
            $user->eraseCredentials();
        }
    }

    /**
     * @param UserInterface $user
     *
     * @return PasswordEncoderInterface
     */
    protected function getEncoder(UserInterface $user)
    {
        return $this->encoderFactory->getEncoder($user);
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return boolean
     *
     * @deprecated Use FOS\UserBundle\Security\UserProvider instead
     */
    public function supportsClass($class)
    {
        trigger_error('Using the UserManager as user provider is deprecated. Use FOS\UserBundle\Security\UserProvider instead.', E_USER_DEPRECATED);

        return $class === $this->getClass();
    }
}
