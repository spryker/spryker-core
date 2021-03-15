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

        $moneyValueTransfer = $value->getMoneyValueOrFail();

        if (!($value->getIdProductAbstract() || $value->getIdProduct())) {
            return;
        }

        if (!$moneyValueTransfer->getFkStore() || !$moneyValueTransfer->getFkCurrency()) {
            return;
        }

        $priceProductCriteriaTransfer = (new PriceProductCriteriaTransfer())
            ->setIdProductAbstract($value->getIdProductAbstract())
            ->setIdProduct($value->getIdProduct())
            ->setIdCurrency($moneyValueTransfer->getFkCurrency())
            ->setIdStore($moneyValueTransfer->getFkStore())
            ->setPriceType($value->getPriceType()->getNameOrFail());

        $priceProductTransfers = $constraint->getPriceProductRepository()->getProductPricesByCriteria($priceProductCriteriaTransfer);

        if (
            $priceProductTransfers->count() > 1
            || ($priceProductTransfers->count() === 1
                && $priceProductTransfers->offsetGet(0)->getMoneyValue()->getIdEntity() !== $value->getMoneyValue()->getIdEntity())
        ) {
            $this->context->addViolation($constraint->getMessage());
        }
    }
}
