<?php

namespace Chaplean\Bundle\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class MinimalPasswordRequirementsValidator.
 *
 * @package   App\Bundle\RestBundle\Validator\Constraints
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2016 Chaplean (http://www.chaplean.coop)
 * @since     4.0.0
 */
class MinimalPasswordRequirementsValidator extends ConstraintValidator
{
    /**
     * @param string     $value
     * @param Constraint $constraint
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if (strlen($value) < 6) {
            $this->context->addViolation($constraint->tooShort);
        }

        if (preg_match('/^[a-zA-Z0-9]+$/', $value)) {
            $this->context->addViolation($constraint->noSpecialCharacters);
        }
    }
}
