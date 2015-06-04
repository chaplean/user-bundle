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

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\SessionCsrfProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security as SecurityContext;

class Security
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * Constructor.
     *
     * @param Container $container Container.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Return the parameter for page login and error login
     *
     * @return array
     */
    public function getParametersLogin()
    {
        $helper = $this->container->get('security.authentication_utils');

        return array(
            'last_username' => $helper->getLastUsername(),
            'error'         => $helper->getLastAuthenticationError(),
        );
    }
}
