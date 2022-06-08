<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\DummyMarketplacePayment\Form\Constraint;

use InvalidArgumentException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DateOfBirthValueConstraintValidator extends ConstraintValidator
{
    /**
     * @param mixed $value Date of birth that should be validated.
     * @param \Symfony\Component\Validator\Constraint|\Spryker\Yves\DummyMarketplacePayment\Form\Constraint\DateOfBirthValueConstraint $constraint
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof DateOfBirthValueConstraint) {
            throw new InvalidArgumentException(sprintf(
                'Expected constraint instance of %s, got %s instead.',
                DateOfBirthValueConstraint::class,
                get_class($constraint),
            ));
        }

        if ($value === null) {
            return;
        }

        if ($this->isValidValue($value, $constraint->getMinimalDate())) {
            return;
        }

        $this->context->buildViolation($constraint->getMessage())->addViolation();
    }

    /**
     * @param string $dateOfBirth
     * @param string $minimalDate
     *
     * @return bool
     */
    protected function isValidValue(string $dateOfBirth, string $minimalDate): bool
    {
        return strtotime($dateOfBirth) < strtotime($minimalDate);
    }
}
