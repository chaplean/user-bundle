<?php

namespace Chaplean\Bundle\UserBundle\Utility;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * SecurityUtility.php.
 *
 * @package   Chaplean\Bundle\UserBundle\Utility
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.coop)
 * @since     0.1.0
 */
class SecurityUtility
{
    /**
     * @var AuthenticationUtils
     */
    protected $authenticationUtils;

    /**
     * Constructor.
     *
     * @param AuthenticationUtils $authenticationUtils
     */
    public function __construct(AuthenticationUtils $authenticationUtils)
    {
        $this->authenticationUtils = $authenticationUtils;
    }

    /**
     * Return the parameter for page login and error login
     *
     * @return array
     */
    public function getParametersLogin()
    {
        return [
            'last_username' => $this->authenticationUtils->getLastUsername(),
            'error'         => $this->authenticationUtils->getLastAuthenticationError(),
        ];
    }
}
