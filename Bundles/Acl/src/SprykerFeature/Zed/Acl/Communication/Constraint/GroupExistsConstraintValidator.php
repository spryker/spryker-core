<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Communication\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class GroupExistsConstraintValidator extends ConstraintValidator
{

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $exists = $constraint->getLocator()->acl()->facade()->hasGroupByName($value);

        if ($exists === true && $constraint->getGroupId()) {
            $original = $constraint->getLocator()->acl()->facade()->getGroup($constraint->getGroupId());
        }

        if (true === $exists && $constraint->getGroupId() && $original->getName() !== $value) {
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
