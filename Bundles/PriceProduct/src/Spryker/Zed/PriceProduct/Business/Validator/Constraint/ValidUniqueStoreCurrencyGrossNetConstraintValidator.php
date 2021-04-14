<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Validator\Constraint;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\Kernel\Communication\Validator\AbstractConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ValidUniqueStoreCurrencyGrossNetConstraintValidator extends AbstractConstraintValidator
{
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

        if (!$constraint instanceof ValidUniqueStoreCurrencyGrossNetConstraint) {
            throw new UnexpectedTypeException($constraint, ValidUniqueStoreCurrencyGrossNetConstraint::class);
        }

        /** @var \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer */
        $moneyValueTransfer = $value->getMoneyValueOrFail();

        if (!$value->getIdProductAbstract() && !$value->getIdProduct()) {
            return;
        }

        if (!$moneyValueTransfer->getFkStore() || !$moneyValueTransfer->getFkCurrency()) {
            return;
        }

        /** @var \Generated\Shared\Transfer\PriceTypeTransfer $priceTypeTransfer */
        $priceTypeTransfer = $value->getPriceTypeOrFail();

        $priceProductCriteriaTransfer = (new PriceProductCriteriaTransfer())
            ->setIdProductAbstract($value->getIdProductAbstract())
            ->setIdProductConcrete($value->getIdProduct())
            ->setIdCurrency($moneyValueTransfer->getFkCurrency())
            ->setIdStore($moneyValueTransfer->getFkStore())
            ->setPriceType($priceTypeTransfer->getNameOrFail());

        $priceProductTransfers = $constraint->getPriceProductRepository()->getProductPricesByCriteria($priceProductCriteriaTransfer);
        /** @var \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer */
        $priceProductTransfer = $priceProductTransfers->offsetGet(0);
        /** @var \Generated\Shared\Transfer\MoneyValueTransfer $priceProductMoneyValueTransfer */
        $priceProductMoneyValueTransfer = $priceProductTransfer->getMoneyValue();
        if (
            $priceProductTransfers->count() > 1
            || ($priceProductTransfers->count() === 1
                && $priceProductMoneyValueTransfer->getIdEntity() !== $moneyValueTransfer->getIdEntity())
        ) {
            $this->context->addViolation($constraint->getMessage());
        }
    }
}
