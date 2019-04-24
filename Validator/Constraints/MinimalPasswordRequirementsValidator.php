<?php

namespace Chaplean\Bundle\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class MinimalPasswordRequirementsValidator.
 *
 * @package   App\Bundle\RestBundle\Validator\Constraints
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2016 Chaplean (https://www.chaplean.coopn.coop)
 * @since     4.0.0
 */
class MinimalPasswordRequirementsValidator extends ConstraintValidator
{
    /**
     * @param string                                 $value
     * @param Constraint|MinimalPasswordRequirements $constraint
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if (strlen($value) < $constraint->minLength) {
            $this->context->addViolation($constraint->tooShort);
            return;
        }

        if ($constraint->atLeastOneSpecialCharacter && preg_match('/^[a-zA-Z0-9]+$/', $value)) {
            $this->context->addViolation($constraint->noSpecialCharacters);
            return;
        }
    }
}
