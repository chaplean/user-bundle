<?php

namespace Chaplean\Bundle\UserBundle\Email;

/**
 * Interface EmailInterface.
 *
 * @package   App\Bundle\RestBundle\Email
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2017 Chaplean (https://www.chaplean.coopn.coop)
 * @since     7.1.0
 */
interface EmailInterface
{
    /**
     * @return \Swift_Message
     */
    public function getEmail();
}
