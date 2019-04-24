<?php

namespace Chaplean\Bundle\UserBundle\Event;

use Chaplean\Bundle\UserBundle\Model\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class ChapleanUserCreatedEvent.
 *
 * This event is dispatched each time after a user is created.
 *
 * @package   Chaplean\Bundle\UserBundle\Event
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2017 Chaplean (https://www.chaplean.coopn.coop)
 * @since     5.0.1
 */
class ChapleanUserCreatedEvent extends Event
{
    const NAME = 'chaplean.user.created';

    /**
     * @var User
     */
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
