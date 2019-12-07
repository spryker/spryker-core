<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PriceProductSchedulePriceConstraintValidator extends ConstraintValidator
{
    protected const NET_AMOUNT_PATH = 'priceProduct.moneyValue.netAmount';

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $value
     * @param \Spryker\Zed\PriceProductScheduleGui\Communication\Form\Constraint\PriceProductSchedulePriceConstraint $constraint
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        $this->assertValueType($value);

        if ($this->assertPrices($value)) {
            return;
        }

        $this->context
            ->buildViolation($constraint->getMessage())
            ->atPath(static::NET_AMOUNT_PATH)
            ->addViolation();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return bool
     */
    protected function assertPrices(PriceProductScheduleTransfer $priceProductScheduleTransfer): bool
    {
        $priceProductTransfer = $priceProductScheduleTransfer->getPriceProduct();
        if ($priceProductTransfer === null) {
            return false;
        }
        $moneyValueTransfer = $priceProductTransfer->getMoneyValue();
        if ($moneyValueTransfer === null) {
            return false;
        }

        return $moneyValueTransfer->getNetAmount() !== null ||
            $moneyValueTransfer->getGrossAmount() !== null;
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
