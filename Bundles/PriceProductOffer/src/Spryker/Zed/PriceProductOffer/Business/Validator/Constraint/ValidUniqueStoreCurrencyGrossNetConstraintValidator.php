<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business\Validator\Constraint;

use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\Kernel\Communication\Validator\AbstractConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ValidUniqueStoreCurrencyGrossNetConstraintValidator extends AbstractConstraintValidator
{
    /**
     * Checks if the Valid from value is earlier than Valid to.
     *
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

        if (
            !$value->getPriceDimension()
            || !$value->getPriceDimension()->getIdProductOffer()
            || !$moneyValueTransfer->getFkStore()
            || !$moneyValueTransfer->getFkCurrency()
        ) {
            return;
        }

        $priceProductOfferCriteriaTransfer = new PriceProductOfferCriteriaTransfer();
        $priceProductOfferCriteriaTransfer->setIdProductOffer($value->getPriceDimension()->getIdProductOffer())
            ->addIdCurrency($moneyValueTransfer->getFkCurrency())
            ->addIdStore($moneyValueTransfer->getFkStore())
            ->addIdPriceType($value->getPriceType()->getIdPriceTypeOrFail());

        $priceProductTransfers = $constraint->getPriceProductOfferRepository()->getProductOfferPrices($priceProductOfferCriteriaTransfer);
        if (
            $priceProductTransfers->count() > 1
            || ($priceProductTransfers->count() === 1
                && $priceProductTransfers->offsetGet(0)->getMoneyValue()->getIdEntity() !== $value->getMoneyValue()->getIdEntity())
        ) {
            $this->context->addViolation($constraint->getMessage());
        }
    }
}
