<?php

namespace Chaplean\Bundle\UserBundle\Utility;

use Chaplean\Bundle\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\DependencyInjection\Container;

/**
 * PasswordUtility.php.
 *
 * @package   Chaplean\Bundle\UserBundle\Utility
 * @author    Matthias - Chaplean <matthias@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     0.1.0
 */
class PasswordUtility
{
    /** @var UserManagerInterface $userManager */
    protected $userManager;

    /** @var TokenGeneratorInterface $tokenGenerator */
    protected $tokenGenerator;

    /**
     * Constructor.
     *
     * @param Container $serviceContainer serviceContainer.
     */
    public function __construct(UserManagerInterface $userManager, TokenGeneratorInterface $tokenGenerator)
    {
        $this->userManager = $userManager;
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * Wether or not the given set password token is valid for a user
     *
     * @param string $token
     *
     * @return boolean
     */
    public function isTokenValid($token)
    {
        $user = $this->userManager->findOneBy(array('confirmationToken' => $token));

        // Token found and not requested more than 48h ago
        return $user !== null && $user->isPasswordRequestNonExpired(48 * 3600);
    }

    /**
     * Create the confirmation token for the given user
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function createConfirmationToken(UserInterface $user)
    {
        $token = $this->tokenGenerator->generateToken();
        $user->setConfirmationToken($token);
        $user->setPasswordRequestedAt(new \DateTime('now'));
    }

    /**
     * Set the given $password for the given $user and invalidate the reset password token
     *
     * @param UserInterface $user
     * @param               $password
     *
     * @return void
     */
    public function setPassword(UserInterface $user, $password)
    {
        $user->setPlainPassword($password);
        $user->setConfirmationToken(null);
    }
}
