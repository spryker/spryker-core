<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\Mapper;

use Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer;
use Generated\Shared\Transfer\PriceProductMerchantRelationshipValueTransfer;

class CompanyBusinessUnitPriceProductMapper implements CompanyBusinessUnitPriceProductMapperInterface
{
    protected const PRICE_KEY_SEPARATOR = ':';

    /**
     * @param array $priceProductMerchantRelationshipsData
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[]
     */
    public function mapPriceProductMerchantRelationshipArrayToTransfers(array $priceProductMerchantRelationshipsData): array
    {
        $pricesByKey = [];
        foreach ($priceProductMerchantRelationshipsData as $priceProductMerchantRelationshipData) {
            $uniquePriceIndex = $this->createUniquePriceIndex($priceProductMerchantRelationshipData);
            if (!isset($pricesByKey[$uniquePriceIndex])) {
                $pricesByKey[$uniquePriceIndex] = $this->createPriceProductMerchantRelationshipStorageTransfer(
                    $priceProductMerchantRelationshipData,
                    $uniquePriceIndex
                );
            }

            $this->addUngroupedPrice($pricesByKey[$uniquePriceIndex], $priceProductMerchantRelationshipData);
        }

        return $pricesByKey;
    }

    /**
     * @param array $priceProductMerchantRelationshipData
     * @param string $uniquePriceIndex
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer
     */
    protected function createPriceProductMerchantRelationshipStorageTransfer(
        array $priceProductMerchantRelationshipData,
        string $uniquePriceIndex
    ): PriceProductMerchantRelationshipStorageTransfer {
        return (new PriceProductMerchantRelationshipStorageTransfer())
            ->fromArray($priceProductMerchantRelationshipData, true)
            ->setPriceKey($uniquePriceIndex)
            ->setIdMerchantRelationship(null);
    }

    /**
     * @param array $priceProductMerchantRelationshipData
     *
     * @return string
     */
    protected function createUniquePriceIndex(array $priceProductMerchantRelationshipData): string
    {
        return implode(static::PRICE_KEY_SEPARATOR, [
            $priceProductMerchantRelationshipData[PriceProductMerchantRelationshipStorageTransfer::STORE_NAME],
            $priceProductMerchantRelationshipData[PriceProductMerchantRelationshipStorageTransfer::ID_PRODUCT],
            $priceProductMerchantRelationshipData[PriceProductMerchantRelationshipStorageTransfer::ID_COMPANY_BUSINESS_UNIT],
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer $merchantRelationshipStorageTransfer
     * @param array $priceProductMerchantRelationshipData
     *
     * @return void
     */
    protected function addUngroupedPrice(
        PriceProductMerchantRelationshipStorageTransfer $merchantRelationshipStorageTransfer,
        array $priceProductMerchantRelationshipData
    ): void {
        $merchantRelationshipStorageTransfer->addUngroupedPrice(
            (new PriceProductMerchantRelationshipValueTransfer())
                ->fromArray($priceProductMerchantRelationshipData, true)
        );
    }
}
