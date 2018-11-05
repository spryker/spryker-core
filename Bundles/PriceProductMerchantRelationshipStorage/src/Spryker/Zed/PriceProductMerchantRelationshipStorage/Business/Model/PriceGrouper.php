<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model;

use Spryker\Zed\PriceProductMerchantRelationshipStorage\PriceProductMerchantRelationshipStorageConfig;

class PriceGrouper implements PriceGrouperInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[] $priceProductMerchantRelationshipStorageTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[]
     */
    public function groupPrices(array $priceProductMerchantRelationshipStorageTransfers): array
    {
        foreach ($priceProductMerchantRelationshipStorageTransfers as $priceProductMerchantRelationshipStorageTransfer) {
            $groupedPrices = [];
            foreach ($priceProductMerchantRelationshipStorageTransfer->getUngroupedPrices() as $price) {
                $groupedPrices[$price->getIdMerchantRelationship()][$price->getCurrencyCode()][PriceProductMerchantRelationshipStorageConfig::PRICE_DATA] = $price->getPriceData();

                if ($price->getGrossPrice()) {
                    $groupedPrices[$price->getIdMerchantRelationship()][$price->getCurrencyCode()][PriceProductMerchantRelationshipStorageConfig::PRICE_MODE_GROSS][$price->getPriceType()] = $price->getGrossPrice();
                }

                if ($price->getNetPrice()) {
                    $groupedPrices[$price->getIdMerchantRelationship()][$price->getCurrencyCode()][PriceProductMerchantRelationshipStorageConfig::PRICE_MODE_NET][$price->getPriceType()] = $price->getNetPrice();
                }
            }

            $priceProductMerchantRelationshipStorageTransfer->setPrices($groupedPrices);
        }

        return $priceProductMerchantRelationshipStorageTransfers;
    }
}
