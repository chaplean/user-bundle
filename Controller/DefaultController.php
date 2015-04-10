<?php

namespace Chaplean\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController.
 *
 * @package   Chaplean\Bundle\UserBundle\Controller
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     0.1.0
 */
class DefaultController extends Controller
{
    /**
     * Index action.
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('ChapleanUserBundle:Default:index.html.twig');
    }
}
