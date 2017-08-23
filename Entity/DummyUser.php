<?php

namespace Chaplean\Bundle\UserBundle\Entity;

use Chaplean\Bundle\UserBundle\Doctrine\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class User.
 *
 * @package   Chaplean\Bundle\UserBundle\Entity
 * @author    Benoit - Chaplean <benoit@chaplean.coop>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.coop)
 * @since     2.0.0
 *
 * @ORM\Entity(repositoryClass="Chaplean\Bundle\UserBundle\Repository\UserRepository")
 * @ORM\Table(name="app_user")
 */
class DummyUser extends User
{
}
