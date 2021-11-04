<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolume\Business\Validator\Constraint;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class DefaultPriceTypeConstraintValidator extends ConstraintValidator
{
    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_TYPE_DEFAULT
     *
     * @var string
     */
    protected const PRICE_TYPE_DEFAULT = 'DEFAULT';

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof PriceProductTransfer) {
            throw new UnexpectedTypeException($value, PriceProductTransfer::class);
        }

        if (!$constraint instanceof DefaultPriceTypeConstraint) {
            throw new UnexpectedTypeException($constraint, DefaultPriceTypeConstraint::class);
        }

        $priceProductTransfer = $value;

        $priceTypeName = $priceProductTransfer
            ->getPriceTypeOrFail()
            ->getNameOrFail();

        if ($priceProductTransfer->getVolumeQuantity() <= 1 || $priceTypeName === static::PRICE_TYPE_DEFAULT) {
            return;
        }

        $moneyValueTransfer = $value->getMoneyValueOrFail();

        if ($moneyValueTransfer->getGrossAmount() !== null) {
            $this->addViolationForMoneyValueType($constraint, MoneyValueTransfer::GROSS_AMOUNT);
        }
        if ($moneyValueTransfer->getNetAmount() !== null) {
            $this->addViolationForMoneyValueType($constraint, MoneyValueTransfer::NET_AMOUNT);
        }
    }

    /**
     * @param \Spryker\Zed\PriceProductVolume\Business\Validator\Constraint\DefaultPriceTypeConstraint $constraint
     * @param string $moneyValueType
     *
     * @return void
     */
    protected function addViolationForMoneyValueType(
        DefaultPriceTypeConstraint $constraint,
        string $moneyValueType
    ): void {
        $propertyPath = $this->createPropertyPath($moneyValueType);

        $this->context
            ->buildViolation($constraint->getMessage())
            ->atPath($propertyPath)
            ->addViolation();
    }

    /**
     * @param string $moneyValueType
     *
     * @return string
     */
    protected function createPropertyPath(string $moneyValueType): string
    {
        return sprintf('[%s][%s]', PriceProductTransfer::MONEY_VALUE, $moneyValueType);
    }
}
