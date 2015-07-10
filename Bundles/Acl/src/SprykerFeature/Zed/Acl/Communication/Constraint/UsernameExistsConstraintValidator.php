<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Communication\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UsernameExistsConstraintValidator extends ConstraintValidator
{

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $exists = $constraint->getLocator()->user()->facade()->hasUserByUsername($value);

        if ($exists === true && $constraint->getIdUser() !== null) {
            $original = $constraint->getLocator()->user()->facade()->getUserById($constraint->getIdUser());
        }

        if (true === $exists && $constraint->getIdUser() !== null && $original->getUsername() !== $value) {
            $this->addViolation($value, $constraint);
        }
    }

    /**
     * @param string $value
     * @param Constraint $constraint
     */
    protected function addViolation($value, Constraint $constraint)
    {
        $this->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }

}
