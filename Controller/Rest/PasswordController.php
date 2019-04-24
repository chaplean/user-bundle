<?php

namespace Chaplean\Bundle\UserBundle\Controller\Rest;

use Chaplean\Bundle\UserBundle\Form\Handler\SetPasswordSuccessHandler;
use Chaplean\Bundle\UserBundle\Form\Type\RequestResetPasswordType;
use Chaplean\Bundle\UserBundle\Form\Type\SetPasswordType;
use Chaplean\Bundle\UserBundle\Model\SetPasswordModel;
use Chaplean\Bundle\UserBundle\Model\UserInterface;
use Chaplean\Bundle\UserBundle\Utility\RegistrationUtility;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PasswordController.
 *
 * @package   App\Bundle\RestBundle\Controller
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2016 Chaplean (https://www.chaplean.coopn.coop)
 * @since     4.0.0
 *
 * @Annotations\RouteResource("Password")
 */
class PasswordController extends FOSRestController
{
    /**
     * Handles the request reset password form post
     * Sends an email with a reset password token if the user with the given email is found
     * Doesn't return wether or not the user was found
     *
     * @Annotations\Post("/password/request_reset")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function postRequestResetPasswordAction(Request $request)
    {
        if ($this->getUser() !== null) {
            return $this->handleView(new View('', Response::HTTP_FORBIDDEN));
        }

        $form = $this->createForm(RequestResetPasswordType::class);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $passwordUtility = $this->get('chaplean_user.password_utility');
            $registrationUtility = $this->get(RegistrationUtility::class);

            $formData = $form->getData();
            $userManager = $this->get('chaplean_user.user_manager');
            /** @var UserInterface $user */
            $user = $userManager->findUserBy(['email' => $formData['email']]);

            if ($user === null) {
                return $this->handleView(new View(['error' => 'user_not_found'], Response::HTTP_BAD_REQUEST));
            }

            $passwordUtility->createConfirmationToken($user);
            $userManager->updateUser($user);
            $registrationUtility->sendResettingMailForUser($user);

            $this->get('doctrine')->getManager()->flush();

            return $this->handleView(new View());
        }

        return $this->handleView(new View(['error' => 'invalid_form'], Response::HTTP_BAD_REQUEST));
    }

    /**
     * Handles the set password form post
     * Updates the password if the user with the given and token is found
     *
     * @Annotations\Post("/password/set")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function postSetPasswordAction(Request $request)
    {
        if ($this->getUser() !== null) {
            return $this->handleView($this->view('', Response::HTTP_UNAUTHORIZED));
        }

        return $this->get('chaplean_form_handler.form.controller_form_handler')
            ->successHandler(SetPasswordSuccessHandler::class)
            ->handle(SetPasswordType::class, new SetPasswordModel() ,$request);
    }
}
