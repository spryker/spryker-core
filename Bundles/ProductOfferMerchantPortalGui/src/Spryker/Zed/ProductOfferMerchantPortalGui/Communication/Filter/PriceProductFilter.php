<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Filter;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;

class PriceProductFilter implements PriceProductFilterInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function filterPriceProductTransfers(
        array $priceProductTransfers,
        PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
    ): array {
        foreach ($priceProductTransfers as $index => $priceProductTransfer) {
            $moneyValueTransfer = $priceProductTransfer->getMoneyValueOrFail();

            if (
                $this->getIsMatchingCurrency($moneyValueTransfer, $priceProductOfferCriteriaTransfer)
                && $this->getIsMatchingStore($moneyValueTransfer, $priceProductOfferCriteriaTransfer)
                && $this->getIsMatchingPriceType($priceProductTransfer, $priceProductOfferCriteriaTransfer)
                && $this->getIsMatchingVolumeQuantity($priceProductTransfer, $priceProductOfferCriteriaTransfer)
                && $this->getIsMatchingPriceProductOfferIds($priceProductTransfer, $priceProductOfferCriteriaTransfer)
            ) {
                continue;
            }

            unset($priceProductTransfers[$index]);
        }

        return $priceProductTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     * @param \Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
     *
     * @return bool
     */
    protected function getIsMatchingCurrency(
        MoneyValueTransfer $moneyValueTransfer,
        PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
    ): bool {
        if (empty($priceProductOfferCriteriaTransfer->getCurrencyIds())) {
            return true;
        }

        return in_array(
            $moneyValueTransfer->getFkCurrency(),
            $priceProductOfferCriteriaTransfer->getCurrencyIds()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     * @param \Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
     *
     * @return bool
     */
    protected function getIsMatchingStore(
        MoneyValueTransfer $moneyValueTransfer,
        PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
    ): bool {
        if (empty($priceProductOfferCriteriaTransfer->getStoreIds())) {
            return true;
        }

        return in_array(
            $moneyValueTransfer->getFkStore(),
            $priceProductOfferCriteriaTransfer->getStoreIds()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
     *
     * @return bool
     */
    protected function getIsMatchingPriceType(
        PriceProductTransfer $priceProductTransfer,
        PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
    ): bool {
        if (empty($priceProductOfferCriteriaTransfer->getPriceTypeIds())) {
            return true;
        }

        return in_array(
            $priceProductTransfer->getPriceTypeOrFail()->getIdPriceType(),
            $priceProductOfferCriteriaTransfer->getPriceTypeIds()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
     *
     * @return bool
     */
    protected function getIsMatchingVolumeQuantity(
        PriceProductTransfer $priceProductTransfer,
        PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
    ): bool {
        if (empty($priceProductOfferCriteriaTransfer->getVolumeQuantities())) {
            return true;
        }

        return in_array(
            $priceProductTransfer->getVolumeQuantity(),
            $priceProductOfferCriteriaTransfer->getVolumeQuantities()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
     *
     * @return bool
     */
    protected function getIsMatchingPriceProductOfferIds(
        PriceProductTransfer $priceProductTransfer,
        PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
    ): bool {
        if (empty($priceProductOfferCriteriaTransfer->getPriceProductOfferIds())) {
            return true;
        }

        return in_array(
            $priceProductTransfer->getPriceDimensionOrFail()->getIdPriceProductOffer(),
            $priceProductOfferCriteriaTransfer->getPriceProductOfferIds()
        );
    }
}
