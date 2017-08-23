<?php

namespace Chaplean\Bundle\UserBundle\Doctrine;

use Chaplean\Bundle\UserBundle\Model\AbstractUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class User.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 *
 * @ORM\MappedSuperclass
 */
class User extends AbstractUser
{
}
