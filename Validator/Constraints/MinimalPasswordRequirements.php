<?php

namespace Chaplean\Bundle\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class MinimalPasswordRequirements.
 *
 * @package   App\Bundle\RestBundle\Validator\Constraints
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2016 Chaplean (https://www.chaplean.coopn.coop)
 * @since     4.0.0
 */
class MinimalPasswordRequirements extends Constraint
{
    public $tooShort = 'form.set_password.error.too_short';
    public $noSpecialCharacters = 'form.set_password.error.no_special_characters';

    public $minLength = 6;
    public $atLeastOneSpecialCharacter = true;
}
