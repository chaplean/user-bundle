<?php

namespace Chaplean\Bundle\UserBundle\Controller;

use Chaplean\Bundle\UserBundle\Doctrine\UserManager;
use Chaplean\Bundle\UserBundle\Form\Type\ProfileFormType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Chaplean\Bundle\UserBundle\Doctrine\User;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class ProfileController extends BaseController
{
    /**
     * @param Request $request Request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $indexUrl = $this->container->getParameter('chaplean_user.controller.index_path');

        if (!$request->isXmlHttpRequest()) {
            return $this->redirect($this->generateUrl($indexUrl));
        }
        /** @var $userManager UserManager */
        $userManager = $this->get('chaplean_user.user_manager');

        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(new ProfileFormType(), $user);

        if ($request->getMethod() == 'POST') {
            $form->setData($user);
            $form->handleRequest($request);

            if ($form->isValid()) {
                // update user in database
                $userManager->updateUser($user);

                // update user session
                $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());

                $this->get('security.token_storage')->setToken($token);

                return new JsonResponse(array(
                    'success' => true,
                    'message' => '',
                    'data'    => array(
                        'redirect' => $this->generateUrl($indexUrl)
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
            $enumTitle = User::getEnumTitle();
            $title = $user->getTitle();
            return $this->render(
                'ChapleanUserBundle:Registration:register.html.twig',
                array(
                    'form' => $form->createView(),
                    'update' => true,
                    'userTitle' => array_keys($enumTitle, $title)[0],
                )
            );
        }
    }
}
