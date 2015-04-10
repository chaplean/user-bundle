<?php
/**
 * Security.php.
 *
 * @package   Chaplean\Bundle\UserBundle\Utility
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     0.1.0
 */

namespace Chaplean\Bundle\UserBundle\Utility;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;

class Security
{
    protected $serviceContainer;

    /**
     * Constructor.
     *
     * @param Service $serviceContainer serviceContainer.
     */
    public function __construct($serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * Return the parameter for page login and error login
     *
     * @param Request $request
     *
     * @return array
     */
    public function getParametersLogin($request)
    {
        /** @var $session Session */
        $session = $request->getSession();

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContextInterface::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContextInterface::LAST_USERNAME);

        $csrfToken = $this->serviceContainer->has('form.csrf_provider') ? $this->serviceContainer->get('form.csrf_provider')->generateCsrfToken('authenticate') : null;

        return array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'csrf_token'    => $csrfToken,
        );
    }
}
