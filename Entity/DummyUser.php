<?php

namespace Chaplean\Bundle\UserBundle\Entity;

use Chaplean\Bundle\UserBundle\Doctrine\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User.
 *
 * @package   Chaplean\Bundle\UserBundle\Entity
 * @author    Benoit - Chaplean <benoit@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     2.0.0
 *
 * @ORM\Entity(repositoryClass="Chaplean\Bundle\UserBundle\Repository\UserRepository")
 * @ORM\Table(name="app_user")
 */
class DummyUser extends User
{
}
