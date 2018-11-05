<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\Mapper;

use Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer;
use Generated\Shared\Transfer\PriceProductMerchantRelationshipValueTransfer;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\Generator\PriceKeyGeneratorInterface;

class CompanyBusinessUnitPriceProductMapper implements CompanyBusinessUnitPriceProductMapperInterface
{
    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\Generator\PriceKeyGeneratorInterface
     */
    protected $priceKeyGenerator;

    /**
     * @param \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\Generator\PriceKeyGeneratorInterface $priceKeyGenerator
     */
    public function __construct(PriceKeyGeneratorInterface $priceKeyGenerator)
    {
        $this->priceKeyGenerator = $priceKeyGenerator;
    }

    /**
     * @param array $priceProductMerchantRelationships
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[]
     */
    public function mapPriceProductMerchantRelationshipArrayToTransfers(array $priceProductMerchantRelationships): array
    {
        $pricesByKey = [];
        foreach ($priceProductMerchantRelationships as $priceProductMerchantRelationship) {
            $uniquePriceIndex = $this->createUniquePriceIndex($priceProductMerchantRelationship);
            if (!isset($pricesByKey[$uniquePriceIndex])) {
                $pricesByKey[$uniquePriceIndex] = $this->createPriceProductMerchantRelationshipStorageTransfer($priceProductMerchantRelationship, $uniquePriceIndex);
            }

            $this->addUngroupedPrice($pricesByKey[$uniquePriceIndex], $priceProductMerchantRelationship);
        }

        return $pricesByKey;
    }

    /**
     * @param array $priceProductMerchantRelationship
     * @param string $uniquePriceIndex
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer
     */
    protected function createPriceProductMerchantRelationshipStorageTransfer(
        array $priceProductMerchantRelationship,
        string $uniquePriceIndex
    ): PriceProductMerchantRelationshipStorageTransfer {
        return (new PriceProductMerchantRelationshipStorageTransfer())
            ->fromArray($priceProductMerchantRelationship, true)
            ->setPriceKey($uniquePriceIndex)
            ->setIdMerchantRelationship(null);
    }

    /**
     * @param array $priceProductMerchantRelationship
     *
     * @return string
     */
    protected function createUniquePriceIndex(array $priceProductMerchantRelationship): string
    {
        return $this->priceKeyGenerator
            ->buildPriceKey(
                $priceProductMerchantRelationship[PriceProductMerchantRelationshipStorageTransfer::STORE_NAME],
                $priceProductMerchantRelationship[PriceProductMerchantRelationshipStorageTransfer::ID_PRODUCT],
                $priceProductMerchantRelationship[PriceProductMerchantRelationshipStorageTransfer::ID_COMPANY_BUSINESS_UNIT]
            );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer $merchantRelationshipStorageTransfer
     * @param array $priceProductMerchantRelationship
     *
     * @return void
     */
    protected function addUngroupedPrice(
        PriceProductMerchantRelationshipStorageTransfer $merchantRelationshipStorageTransfer,
        array $priceProductMerchantRelationship
    ): void {
        $merchantRelationshipStorageTransfer->addUngroupedPrice(
            (new PriceProductMerchantRelationshipValueTransfer())
                ->fromArray($priceProductMerchantRelationship, true)
        );
    }
}
