<?php

namespace Chaplean\Bundle\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class MinimalPasswordRequirements.
 *
 * @package   App\Bundle\RestBundle\Validator\Constraints
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2016 Chaplean (http://www.chaplean.coop)
 * @since     4.0.0
 */
class MinimalPasswordRequirements extends Constraint
{
    public $tooShort = 'form.set_password.error.too_short';
    public $noSpecialCharacters = 'form.set_password.error.no_special_characters';
}
