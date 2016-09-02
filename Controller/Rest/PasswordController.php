<?php

namespace Chaplean\Bundle\UserBundle\Controller\Rest;

use Chaplean\Bundle\UserBundle\Form\Type\RequestResetPasswordType;
use Chaplean\Bundle\UserBundle\Form\Type\SetPasswordType;
use Chaplean\Bundle\UserBundle\Model\SetPasswordModel;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PasswordController.
 *
 * @package   App\Bundle\RestBundle\Controller
 * @author    Matthias - Chaplean <matthias@chaplean.com>
 * @copyright 2014 - 2016 Chaplean (http://www.chaplean.com)
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
        $form = $this->createForm(RequestResetPasswordType::class);
        $form->submit($request->request->all());

        if ($this->getUser() !== null) {
            return $this->handleView(new View('', Codes::HTTP_FORBIDDEN));
        }

        if ($form->isValid()) {
            $passwordUtility = $this->get('chaplean_user.password_utility');
            $registrationUtility = $this->get('chaplean_user.registration');

            $formData = $form->getData();
            $userManager = $this->get('chaplean_user.user_manager');
            $user = $userManager->findUserBy(array('email' => $formData['email']));

            if ($user !== null) {
                $passwordUtility->createConfirmationToken($user);
                $userManager->updateUser($user);
                $registrationUtility->sendResettingMailForUser($user);
            }
        }

        return $this->handleView(new View());
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
        $form = $this->createForm(SetPasswordType::class, new SetPasswordModel());
        $form->submit($request->request->all());

        if ($this->getUser() !== null) {
            return $this->handleView(new View('', Codes::HTTP_FORBIDDEN));
        }

        if (!$form->isValid()) {
            return $this->handleView(new View('', Codes::HTTP_BAD_REQUEST));
        }

        $formData = $form->getData();
        $userManager = $this->get('fos_user.user_manager');
        $passwordUtility = $this->get('chaplean_user.password_utility');

        $user = $userManager->findUserBy(array('confirmationToken' => $formData->getToken()));
        if ($user === null || !$passwordUtility->isTokenValid($formData->getToken())) {
            return $this->handleView(new View('', Codes::HTTP_FORBIDDEN));
        }

        $passwordUtility->setPassword($user, $formData->getPassword());
        $userManager->updateUser($user);

        return $this->handleView(new View());
    }
}
