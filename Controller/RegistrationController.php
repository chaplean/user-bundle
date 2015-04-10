<?php

namespace Chaplean\Bundle\UserBundle\Controller;

use Chaplean\Bundle\UserBundle\Doctrine\UserManager;
use Chaplean\Bundle\UserBundle\Entity\User;
use Chaplean\Bundle\UserBundle\Form\Type\ForgotPasswordFormType;
use Chaplean\Bundle\UserBundle\Form\Type\RegistrationFormType;
use Chaplean\Bundle\UserBundle\Form\Type\ResettingFormType;
use Chaplean\Bundle\UserBundle\Utility\Registration;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class RegistrationController.
 *
 * @package   Chaplean\Bundle\UserBundle\Controller
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     0.1.0
 */
class RegistrationController extends BaseController
{
    /**
     * @param Request $request Request
     *
     * @return Response
     */
    public function registerAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->redirect($this->generateUrl('home'));
        }

        /** @var $userManager UserManager */
        $userManager = $this->get('chaplean_user.user_manager');

        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        /** @var Registration $registrationTool */
        $registrationTool = $this->get('chaplean_user.registration');

        /** @var User $user */
        $user = $userManager->createUser();

        // user not activate default.
        $user->setEnabled(false);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $this->createForm(new RegistrationFormType());

        if ($request->getMethod() == 'POST') {
            $form->setData($user);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $event = new FormEvent($form, $request);

                // send mail registration
                $registrationTool->sendRegistrationMailForUser($user);

                // create
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);
                $userManager->updateUser($user);

                return new JsonResponse(array(
                    'success' => true,
                    'message' => '',
                    'data'    => null
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
                )
            );
        }
    }

    /**
     * Receive the confirmation token from user email provider, log the user
     *
     * @param Request $request
     * @param String  $token
     *
     * @return Response|null
     * @throws NotFoundHttpException
     */
    public function retrieveAction(Request $request, $token)
    {
        /** @var Translator $translator */
        $translator = $this->get('translator');

        if (!$request->isXmlHttpRequest()) {
            return $this->render('ChapleanUserBundle:Registration:confirmed.html.twig', array(
                'render' => $this->forward('OpiiecFrontBundle:Default:home')->getContent()
            ));
        }

        /** @var $userManager UserManager */
        $userManager = $this->get('chaplean_user.user_manager');

        /** @var User $user */
        $user = $userManager->findUserByConfirmationToken($token);

        // create user form with only password fields
        $form = $this->createForm(new ResettingFormType(), $user);

        if (!$user) {
            // message error token is invalid
            return $this->render(
                'OpiiecFrontBundle:Popin:message.html.twig',
                array(
                    'subtitle' => $translator->trans('register.form.password'),
                    'message' => $translator->trans('register.tokenUnvalid')
                )
            );
        } elseif (!$user->isPasswordRequestNonExpired(86400)) {
            // clean user
            $userManager->cleanUser($user);
            $userManager->updateUser($user);

            // message error token is invalid
            return $this->render(
                'OpiiecFrontBundle:Popin:message.html.twig',
                array(
                    'subtitle' => $translator->trans('register.form.password'),
                    'message' => $translator->trans('register.tokenUnvalid')
                )
            );
        } elseif ($request->getMethod() == 'GET') {
            // load popin regsister password
            return $this->render(
                'ChapleanUserBundle:Registration:register-password.html.twig',
                array(
                    'token' => $token,
                    'form' => $form->createView(),
                )
            );
        } elseif ($request->getMethod() == 'POST') {
            // save password
            /** @var $dispatcher EventDispatcherInterface */
            $dispatcher = $this->get('event_dispatcher');

            $form->setData($user);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $userManager->activeUser($user);

                $event = new GetResponseUserEvent($user, $request);

                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRM, $event);
                $userManager->updateUser($user);

                $response = new JsonResponse(array(
                    'success' => true,
                    'message' => '',
                    'data'    => array(
                        'redirect' => $this->generateUrl('home')
                    )
                ));

                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRMED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }

            return new JsonResponse(array(
                'success' => false,
                'message' => '',
                'data'    => array(
                    'token'  => $token,
                    'errors' => $form->getErrors(true)
                )
            ));
        }
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function forgotAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            /** @var $userManager UserManager */
            $userManager = $this->get('chaplean_user.user_manager');

            /** @var Registration $registrationTool */
            $registrationTool = $this->get('chaplean_user.registration');

            /** @var User $user */
            $user = $userManager->createUser();

            /** @var Form $form */
            $form = $this->createForm(new ForgotPasswordFormType());

            if ($request->getMethod() == 'GET') {
                // render form
                return $this->render(
                    'ChapleanUserBundle:Registration:forgot-password.html.twig',
                    array(
                        'form' => $form->createView(),
                    )
                );
            } else {
                $form->setData($user);
                $form->handleRequest($request);

                if ($form->isValid()) {
                    // Check existing email
                    $user = $userManager->findUserByEmail($user->getEmail());

                    if ($user) {
                        $user->setPasswordRequestedAt(new \DateTime('now'));

                        // send email resetting
                        $registrationTool->sendResettingMailForUser($user);

                        $userManager->updateUser($user);
                    }

                    // return success
                    return new JsonResponse(array(
                        'success' => true,
                        'message' => '',
                        'data'    => null
                    ));
                } else {
                    // return error
                    return new JsonResponse(array(
                        'success' => false,
                        'message' => '',
                        'data'    => array(
                            'errors' => $form->getErrors(true)
                        )
                    ));
                }
            }
        }

        // redirection to the homepage for no ajax request
        return $this->redirect($this->generateUrl('home'));
    }
}
