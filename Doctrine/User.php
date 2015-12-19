<?php

namespace Chaplean\Bundle\UserBundle\Doctrine;

use Chaplean\Bundle\UserBundle\Model\AbstractUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class User.
 *
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     1.0.0
 *
 * @ORM\MappedSuperclass
 */
class User extends AbstractUser
{
}
