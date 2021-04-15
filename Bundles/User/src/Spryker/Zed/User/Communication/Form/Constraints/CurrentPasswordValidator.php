<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Communication\Form\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CurrentPasswordValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint|\Spryker\Zed\User\Communication\Form\Constraints\CurrentPassword $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof CurrentPassword) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\Password');
        }

        if (!$this->isProvidedPasswordEqualsToPersisted($value, $constraint)) {
            $this->context->buildViolation($constraint->getMessage())
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->addViolation();
        }
    }

    /**
     * @param string $password
     * @param \Spryker\Zed\User\Communication\Form\Constraints\CurrentPassword $constraint
     *
     * @return bool
     */
    protected function isProvidedPasswordEqualsToPersisted($password, CurrentPassword $constraint)
    {
        $userTransfer = $constraint->getFacadeUser()->getCurrentUser();

        return $constraint->getFacadeUser()
            ->isValidPassword($password, $userTransfer->getPasswordOrFail());
    }
}
