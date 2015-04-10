<?php

namespace Chaplean\Bundle\UserBundle\Controller;

use Chaplean\Bundle\UserBundle\Doctrine\UserManager;
use Chaplean\Bundle\UserBundle\Form\Type\ProfileFormType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Chaplean\Bundle\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class ProfileController.
 *
 * @package   Chaplean\Bundle\UserBundle\Controller
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     0.1.0
 */
class ProfileController extends BaseController
{
    /**
     * @param Request $request Request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->redirect($this->generateUrl('home'));
        }

        /** @var $userManager UserManager */
        $userManager = $this->get('chaplean_user.user_manager');

        /** @var User $user */
        $user = $this->get('security.context')->getToken()->getUser();

        $form = $this->createForm(new ProfileFormType(), $user);

        if ($request->getMethod() == 'POST') {
            $form->setData($user);
            $form->handleRequest($request);

            if ($form->isValid()) {
                // update user in database
                $userManager->updateUser($user);

                // update user session
                $token = new UsernamePasswordToken($user, null, "main", $user->getRoles());
                $this->get('security.context')->setToken($token);

                return $response = new JsonResponse(array(
                    'success' => true,
                    'message' => '',
                    'data'    => array(
                        'redirect' => $this->generateUrl('home')
                    )
                ));
            } else {
                return new JsonResponse(array(
                    'success' => false,
                    'message' => '',
                    'data'    => array(
                        'errors' => $form->getErrors(true)
                    )
                ));
            }
        } else {
            return $this->render(
                'ChapleanUserBundle:Registration:register.html.twig',
                array(
                    'form' => $form->createView(),
                    'update' => true,
                    'userTitle' => array_keys(User::getEnumTitle(), $user->getTitle())[0],
                )
            );
        }
    }
}
