<?php

namespace Chaplean\Bundle\UserBundle\Utility;

use Symfony\Component\Form\FormErrorIterator;

/**
 * Class FormErrorUtility.
 *
 * @package   Chaplean\Bundle\UserBundle\Utility
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (http://www.chaplean.coop)
 */
class FormErrorUtility
{
    /**
     * @param FormErrorIterator $formErrors
     *
     * @return array
     */
    public static function errorsToArray(FormErrorIterator $formErrors)
    {
        $errors = [];

        foreach ($formErrors as $error) {
            $key = '';
            $origin = $error->getOrigin();
            $message = $error->getMessage();

            while ($origin->getParent() !== null) {
                $key = sprintf('[%s]', $origin->getName()) . $key;
                $origin = $origin->getParent();
            }

            $completeKey = $origin->getName() . $key;

            $messagesParameters = $error->getMessageParameters();
            if (array_key_exists('{{ extra_fields }}', $messagesParameters)) {
                $message .= ' (' . implode(', ', $messagesParameters) . ')';
            }

            if ($completeKey === $origin->getName()) {
                $errors[$completeKey][] = $message;
            } else {
                $errors[$completeKey] = $message;
            }

        }

        return $errors;
    }
}
