<?php

namespace Chaplean\Bundle\UserBundle\Controller;

use Chaplean\Bundle\UserBundle\Form\Type\RequestResetPasswordType;
use Chaplean\Bundle\UserBundle\Form\Type\SetPasswordType;
use Chaplean\Bundle\UserBundle\Model\SetPasswordModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class PasswordController.
 *
 * @package   Chaplean\Bundle\UserBundle\Controller
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2016 Chaplean (https://www.chaplean.coopn.coop)
 * @since     4.0.0
 */
class PasswordController extends Controller
{
    /**
     * Displays the request reset password form : the email to send the reset link to
     * Requires to be logged out
     *
     * @return Response
     */
    public function requestResetPasswordFormAction()
    {
        $form = $this->createForm(RequestResetPasswordType::class);

        if ($this->getUser() !== null) {
            throw new AccessDeniedHttpException();
        };

        return $this->render(
            'ChapleanUserBundle:Password:request-reset.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * Displays the set (or reset) password form
     * Requires a valid email and token couple for a User
     * Requires to be logged out
     *
     * @param Request $request
     *
     * @throws AccessDeniedHttpException
     *
     * @return Response
     */
    public function setPasswordFormAction(Request $request)
    {
        $token = $request->query->get('token', '');

        $passwordUtility = $this->get('chaplean_user.password_utility');
        if (!$passwordUtility->isTokenValid($token) || $this->getUser() !== null) {
            throw new AccessDeniedHttpException();
        };

        $setPasswordModel = new SetPasswordModel();
        $setPasswordModel->setToken($token);
        $form = $this->createForm(SetPasswordType::class, $setPasswordModel);

        return $this->render(
            'ChapleanUserBundle:Password:set-password.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
