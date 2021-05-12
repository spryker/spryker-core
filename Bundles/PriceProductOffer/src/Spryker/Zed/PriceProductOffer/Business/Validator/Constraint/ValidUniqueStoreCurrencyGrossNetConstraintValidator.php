<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business\Validator\Constraint;

use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductOfferTransfer;
use Spryker\Zed\Kernel\Communication\Validator\AbstractConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ValidUniqueStoreCurrencyGrossNetConstraintValidator extends AbstractConstraintValidator
{
    /**
     * Checks if the Valid from value is earlier than Valid to.
     *
     * @param \Generated\Shared\Transfer\PriceProductOfferTransfer $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof PriceProductOfferTransfer) {
            throw new UnexpectedTypeException($value, PriceProductOfferTransfer::class);
        }

        if (!$constraint instanceof ValidUniqueStoreCurrencyGrossNetConstraint) {
            throw new UnexpectedTypeException($constraint, ValidUniqueStoreCurrencyGrossNetConstraint::class);
        }

        $priceProductTransfers = $value->getProductOfferOrFail()->getPrices();

        foreach ($priceProductTransfers as $priceProductTransfer) {
            /** @var \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer */
            $moneyValueTransfer = $priceProductTransfer->getMoneyValueOrFail();

            if (!$priceProductTransfer->getPriceDimension()) {
                return;
            }

            /** @var \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceDimensionTransfer */
            $priceDimensionTransfer = $priceProductTransfer->getPriceDimension();
            if (
                !$priceDimensionTransfer->getIdProductOffer()
                || !$moneyValueTransfer->getFkStore()
                || !$moneyValueTransfer->getFkCurrency()
            ) {
                return;
            }

            $priceProductOfferCriteriaTransfer = new PriceProductOfferCriteriaTransfer();
            /** @var int $idCurrency */
            $idCurrency = $moneyValueTransfer->getFkCurrency();
            /** @var int $idStore */
            $idStore = $moneyValueTransfer->getFkStore();
            /** @var \Generated\Shared\Transfer\PriceTypeTransfer $priceTypeTransfer */
            $priceTypeTransfer = $priceProductTransfer->getPriceType();
            /** @var int $idPriceType */
            $idPriceType = $priceTypeTransfer->getIdPriceTypeOrFail();
            $priceProductOfferCriteriaTransfer->setIdProductOffer($priceDimensionTransfer->getIdProductOffer())
                ->addIdCurrency($idCurrency)
                ->addIdStore($idStore)
                ->addIdPriceType($idPriceType);

            $priceProductTransfers = $constraint->getPriceProductOfferRepository()->getProductOfferPrices($priceProductOfferCriteriaTransfer);
            /** @var \Generated\Shared\Transfer\MoneyValueTransfer $priceProductMoneyValueTransfer */
            $priceProductMoneyValueTransfer = $priceProductTransfers->offsetGet(0)->getMoneyValue();
            if (
                $priceProductTransfers->count() > 1
                || ($priceProductTransfers->count() === 1
                    && $priceProductMoneyValueTransfer->getIdEntity() !== $moneyValueTransfer->getIdEntity())
            ) {
                $this->context->addViolation($constraint->getMessage());
            }
        }
    }
}
