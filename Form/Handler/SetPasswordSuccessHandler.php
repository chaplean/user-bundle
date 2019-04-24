<?php

namespace Chaplean\Bundle\UserBundle\Form\Handler;

use Chaplean\Bundle\FormHandlerBundle\Form\SuccessHandlerInterface;
use Chaplean\Bundle\UserBundle\Model\SetPasswordModel;
use Chaplean\Bundle\UserBundle\Model\UserInterface;
use Chaplean\Bundle\UserBundle\Utility\PasswordUtility;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * Class SetPasswordSuccessHandler.
 *
 * @package   Chaplean\Bundle\UserBundle\Form\Hanldler
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coopn.coop)
 */
class SetPasswordSuccessHandler implements SuccessHandlerInterface
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * @var PasswordUtility
     */
    protected $passwordUtility;

    /**
     * SetPasswordSuccessHandler constructor.
     *
     * @param RegistryInterface    $registry
     * @param UserManagerInterface $userManager
     * @param PasswordUtility      $passwordUtility
     */
    public function __construct(RegistryInterface $registry, UserManagerInterface $userManager, PasswordUtility $passwordUtility)
    {
        $this->entityManager = $registry->getManager();
        $this->userManager = $userManager;
        $this->passwordUtility = $passwordUtility;
    }

    /**
     * @param mixed|SetPasswordModel $data
     * @param array                  $parameters
     *
     * @return mixed|void
     */
    public function onSuccess($data, array $parameters)
    {
        /** @var UserInterface $user */
        $user = $this->userManager->findUserBy(['confirmationToken' => $data->getToken()]);

        if ($user === null || !$this->passwordUtility->isTokenValid($data->getToken())) {
            throw new AccessDeniedHttpException();
        }

        $this->passwordUtility->setPassword($user, $data->getPassword());
        $user->setEnabled(true);
        $this->userManager->updateUser($user);

        $this->entityManager->persist($user);
    }
}
