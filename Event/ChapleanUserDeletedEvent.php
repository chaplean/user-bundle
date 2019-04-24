<?php

namespace Chaplean\Bundle\UserBundle\Event;

use Chaplean\Bundle\UserBundle\Model\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class ChapleanUserDeletedEvent.
 *
 * This event is dispatched each time before a user is deleted.
 *
 * @package   Chaplean\Bundle\UserBundle\Event
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2017 Chaplean (https://www.chaplean.coopn.coop)
 * @since     5.0.1
 */
class ChapleanUserDeletedEvent extends Event
{
    const NAME = 'chaplean.user.deleted';

    protected $user;

    /**
     * ChapleanUserCreatedEvent constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
