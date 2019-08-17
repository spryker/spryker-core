<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PriceProductScheduleUniqueConstraintValidator extends ConstraintValidator
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $value
     * @param \Spryker\Zed\PriceProductScheduleGui\Communication\Form\Constraint\PriceProductScheduleUniqueConstraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof PriceProductScheduleUniqueConstraint) {
            throw new UnexpectedTypeException($constraint, PriceProductScheduleUniqueConstraint::class);
        }

        if ($constraint->getPriceProductScheduleRepository()->isPriceProductScheduleUnique($value)) {
            return;
        }

        $this->context
            ->buildViolation($constraint->getMessage())
            ->atPath('name')
            ->addViolation();
    }
}
