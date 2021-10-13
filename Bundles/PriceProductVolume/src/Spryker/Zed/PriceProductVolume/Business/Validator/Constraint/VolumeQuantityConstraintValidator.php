<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolume\Business\Validator\Constraint;

use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class VolumeQuantityConstraintValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, SymfonyConstraint $constraint): void
    {
        if (!$constraint instanceof VolumeQuantityConstraint) {
            throw new UnexpectedTypeException($constraint, VolumeQuantityConstraint::class);
        }

        if (!$this->isValid($value)) {
            $this->context->buildViolation($constraint->getMessage())
                ->addViolation();
        }
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    protected function isValid($value): bool
    {
        return $value && $value >= 1;
    }
}
