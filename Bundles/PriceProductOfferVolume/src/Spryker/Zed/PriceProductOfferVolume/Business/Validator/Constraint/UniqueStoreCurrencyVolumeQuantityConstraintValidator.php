<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferVolume\Business\Validator\Constraint;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductOfferTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Shared\PriceProductOfferVolume\PriceProductOfferVolumeConfig;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueStoreCurrencyVolumeQuantityConstraintValidator extends ConstraintValidator
{
    /**
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

        if (!$constraint instanceof UniqueStoreCurrencyVolumeQuantityConstraint) {
            throw new UnexpectedTypeException($constraint, UniqueStoreCurrencyVolumeQuantityConstraint::class);
        }

        $priceProductTransfers = $value->getProductOfferOrFail()->getPrices();

        $existingKeys = [];
        foreach ($priceProductTransfers as $priceProductIndex => $priceProductTransfer) {
            $volumePriceProductTransfers = $constraint->getPriceProductOfferVolumeService()
                ->extractVolumePrices([$priceProductTransfer]);

            foreach ($volumePriceProductTransfers as $volumePriceIndex => $volumePriceProductTransfer) {
                $moneyValueTransfer = $volumePriceProductTransfer->getMoneyValueOrFail();
                if (!$moneyValueTransfer->getFkCurrency() || !$moneyValueTransfer->getFkStore()) {
                    continue;
                }

                $key = $this->createUniqueKey($moneyValueTransfer, $volumePriceProductTransfer);

                if (in_array($key, $existingKeys, true)) {
                    $this->context->buildViolation($constraint->getMessage())
                        ->atPath($this->createValidationPath($priceProductIndex, $volumePriceIndex))
                        ->addViolation();
                }

                $existingKeys[] = $key;
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $volumePriceProductTransfer
     *
     * @return string
     */
    protected function createUniqueKey(
        MoneyValueTransfer $moneyValueTransfer,
        PriceProductTransfer $volumePriceProductTransfer
    ): string {
        return sprintf(
            '%s-%s-%s-%d',
            $moneyValueTransfer->getFkCurrencyOrFail(),
            $moneyValueTransfer->getFkStoreOrFail(),
            $volumePriceProductTransfer->getPriceTypeOrFail()->getIdPriceTypeOrFail(),
            $volumePriceProductTransfer->getVolumeQuantityOrFail(),
        );
    }

    /**
     * @param int $priceProductIndex
     * @param int $volumePriceIndex
     *
     * @return string
     */
    protected function createValidationPath(
        int $priceProductIndex,
        int $volumePriceIndex
    ): string {
        return sprintf(
            '[%s][%s][%d][%s][%s][%s][%d]',
            PriceProductOfferTransfer::PRODUCT_OFFER,
            ProductOfferTransfer::PRICES,
            $priceProductIndex,
            PriceProductTransfer::MONEY_VALUE,
            MoneyValueTransfer::PRICE_DATA,
            PriceProductOfferVolumeConfig::VOLUME_PRICE_TYPE,
            $volumePriceIndex,
        );
    }
}
