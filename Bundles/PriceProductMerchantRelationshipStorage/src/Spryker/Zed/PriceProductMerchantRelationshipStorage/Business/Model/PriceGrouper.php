<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model;

use Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\PriceProductMerchantRelationshipStorageConfig;

class PriceGrouper implements PriceGrouperInterface
{
    protected const PRICES = 'prices';

    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
     * @param array $existingPricesData
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer
     */
    public function groupAndMergePricesData(
        PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer,
        array $existingPricesData = []
    ): PriceProductMerchantRelationshipStorageTransfer {
        $groupedPrices = [];
        foreach ($priceProductMerchantRelationshipStorageTransfer->getUngroupedPrices() as $priceTransfer) {
            if ($priceTransfer->getGrossPrice() || $priceTransfer->getNetPrice()) {
                $groupedPrices[$priceTransfer->getIdMerchantRelationship()][$priceTransfer->getCurrencyCode()][PriceProductMerchantRelationshipStorageConfig::PRICE_DATA] = $priceTransfer->getPriceData();
            }

            $groupedPrices[$priceTransfer->getIdMerchantRelationship()][$priceTransfer->getCurrencyCode()][PriceProductMerchantRelationshipStorageConfig::PRICE_MODE_GROSS][$priceTransfer->getPriceType()] = $priceTransfer->getGrossPrice();
            $groupedPrices[$priceTransfer->getIdMerchantRelationship()][$priceTransfer->getCurrencyCode()][PriceProductMerchantRelationshipStorageConfig::PRICE_MODE_NET][$priceTransfer->getPriceType()] = $priceTransfer->getNetPrice();
        }

        $groupedPrices = array_replace_recursive($existingPricesData[static::PRICES] ?? [], $groupedPrices);
        $groupedPrices = $this->arrayFilterRecursive($groupedPrices, PriceProductMerchantRelationshipStorageConfig::PRICE_DATA);

        if (!empty($groupedPrices)) {
            $priceProductMerchantRelationshipStorageTransfer->setPrices([
                static::PRICES => $groupedPrices,
            ]);
        }

        return $priceProductMerchantRelationshipStorageTransfer;
    }

    /**
     * @param array $array
     * @param string $excludeKey
     *
     * @return array
     */
    protected function arrayFilterRecursive(array $array, string $excludeKey): array
    {
        $array = array_filter($array, function ($v, $k) use ($excludeKey) {
            if ($k === $excludeKey) {
                return true;
            }

            return !empty($v);
        }, ARRAY_FILTER_USE_BOTH);

        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $value = $this->arrayFilterRecursive($value, $excludeKey);

                if (empty($value) || $value === [$excludeKey => null]) {
                    unset($array[$key]);
                }
            }
        }

        return $array;
    }
}
