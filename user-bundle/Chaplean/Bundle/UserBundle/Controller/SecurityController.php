<?php

namespace Chaplean\Bundle\UserBundle\Controller;

use Chaplean\Bundle\UserBundle\Utility\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Class SecurityController.
 *
 * @package   Chaplean\Bundle\UserBundle\Controller
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     0.1.0
 */
class SecurityController extends Controller
{
    /**
     *  Index action, render default login view.
     *
     * @return Response
     */
    public function indexAction()
    {
        $csrfToken = $this->has('form.csrf_provider') ? $this->get('form.csrf_provider')->generateCsrfToken('authenticate') : null;

        return $this->render(
            'ChapleanUserBundle:Security:login.html.twig',
            array(
                'csrf_token' => $csrfToken,
            )
        );
    }

    /**
     * Login action.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function loginBoxAction(Request $request)
    {
        /** @var Security $security */
        $security = $this->get('chaplean_user.security');

        return $this->render('ChapleanUserBundle:Security:login.html.twig', $security->getParametersLogin($request));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function loginAction(Request $request)
    {
        /** @var Security $security */
        $security = $this->get('chaplean_user.security');

        return $this->render('ChapleanUserBundle:Security:login-register.html.twig', $security->getParametersLogin($request));
    }
}
