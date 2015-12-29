<?php

namespace Chaplean\Bundle\UserBundle\Controller;

use Chaplean\Bundle\UserBundle\Utility\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

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
        return $this->render('ChapleanUserBundle:Security:login.html.twig');
    }

    /**
     * Login action.
     *
     *
     * @return Response
     */
    public function loginBoxAction()
    {
        /** @var Security $security */
        $security = $this->get('chaplean_user.security');

        return $this->render('ChapleanUserBundle:Security:login.html.twig', $security->getParametersLogin());
    }

    /**
     *
     * @return Response
     */
    public function loginAction()
    {
        /** @var Security $security */
        $security = $this->get('chaplean_user.security');

        return $this->render('ChapleanUserBundle:Security:login.html.twig', $security->getParametersLogin());
    }
}
