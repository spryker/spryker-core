<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\PriceProductScheduleForm;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PriceProductScheduleDateConstraintValidator extends ConstraintValidator
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $value
     * @param \Spryker\Zed\PriceProductScheduleGui\Communication\Form\Constraint\PriceProductScheduleDateConstraint $constraint
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        $this->assertValueType($value);

        if ($value->getActiveTo() > $value->getActiveFrom()) {
            return;
        }

        $this->context
            ->buildViolation($constraint->getMessage())
            ->atPath(PriceProductScheduleForm::FIELD_ACTIVE_TO)
            ->addViolation();
    }

    /**
     * @param mixed $value
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    protected function assertValueType($value): void
    {
        if (!$value instanceof PriceProductScheduleTransfer) {
            throw new UnexpectedTypeException($value, PriceProductScheduleTransfer::class);
        }
    }
}
