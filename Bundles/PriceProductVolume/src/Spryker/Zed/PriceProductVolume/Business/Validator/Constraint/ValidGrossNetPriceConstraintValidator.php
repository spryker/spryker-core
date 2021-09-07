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

class ValidGrossNetPriceConstraintValidator extends ConstraintValidator
{
    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_TYPE_DEFAULT
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
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof PriceProductTransfer) {
            throw new UnexpectedTypeException($value, PriceProductTransfer::class);
        }

        if (!$constraint instanceof ValidGrossNetPriceConstraint) {
            throw new UnexpectedTypeException($constraint, ValidGrossNetPriceConstraint::class);
        }

        $priceTypeName = $value->getPriceTypeOrFail()->getNameOrFail();
        if ($priceTypeName !== static::PRICE_TYPE_DEFAULT || $value->getVolumeQuantity() <= 1) {
            return;
        }

        $moneyValueTransfer = $value->getMoneyValueOrFail();

        if (
            $moneyValueTransfer->getGrossAmount() === null
            && $moneyValueTransfer->getNetAmount() === null
        ) {
            $this->context->buildViolation($constraint->getMessage())
                ->atPath($this->createPropertyPath(MoneyValueTransfer::GROSS_AMOUNT))
                ->addViolation();

            $this->context->buildViolation($constraint->getMessage())
                ->atPath($this->createPropertyPath(MoneyValueTransfer::NET_AMOUNT))
                ->addViolation();
        }
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
