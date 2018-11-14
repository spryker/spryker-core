<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model;

use Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer;

class PriceProductConcreteStorageWriter extends AbstractPriceProductMerchantRelationshipStorageWriter implements PriceProductConcreteStorageWriterInterface
{
    /**
     * @deprecated Will be removed without replacement.
     *
     * @param array $businessUnitProducts
     *
     * @return void
     */
    public function publishByBusinessUnitProducts(array $businessUnitProducts): void
    {
        $this->publishByCompanyBusinessUnitIds(array_keys($businessUnitProducts));
    }

    /**
     * @param int[] $companyBusinessUnitIds
     *
     * @return void
     */
    public function publishByCompanyBusinessUnitIds(array $companyBusinessUnitIds): void
    {
        $priceProductMerchantRelationshipStorageTransfers = $this->priceProductMerchantRelationshipStorageRepository
            ->findMerchantRelationshipProductConcretePricesDataByCompanyBusinessUnitIds($companyBusinessUnitIds);

        if (empty($priceProductMerchantRelationshipStorageTransfers)) {
            return;
        }

        $existingStorageEntities = $this->priceProductMerchantRelationshipStorageRepository
            ->findExistingPriceProductConcreteMerchantRelationshipEntitiesByCompanyBusinessUnitIds($companyBusinessUnitIds);

        $this->write($priceProductMerchantRelationshipStorageTransfers, $existingStorageEntities);
    }

    /**
     * @param int[] $priceProductMerchantRelationshipIds
     *
     * @return void
     */
    public function publishConcretePriceProductMerchantRelationship(array $priceProductMerchantRelationshipIds): void
    {
        $priceProductMerchantRelationshipStorageTransfers = $this->priceProductMerchantRelationshipStorageRepository
            ->findMerchantRelationshipProductConcretePricesDataByIds($priceProductMerchantRelationshipIds);

        if (empty($priceProductMerchantRelationshipStorageTransfers)) {
            return;
        }

        $priceKeys = array_map(function (PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer) {
            return $priceProductMerchantRelationshipStorageTransfer->getPriceKey();
        }, $priceProductMerchantRelationshipStorageTransfers);

        $existingStorageEntities = $this->priceProductMerchantRelationshipStorageRepository
            ->findExistingPriceProductConcreteMerchantRelationshipEntitiesByPriceKeys($priceKeys);

        $this->write($priceProductMerchantRelationshipStorageTransfers, $existingStorageEntities, true);
    }

    /**
     * @param int[] $productIds
     *
     * @return void
     */
    public function publishConcretePriceProductByProductIds(array $productIds): void
    {
        $priceProductMerchantRelationshipStorageTransfers = $this->priceProductMerchantRelationshipStorageRepository
            ->findMerchantRelationshipProductConcretePricesDataByProductIds($productIds);

        $existingStorageEntities = $this->priceProductMerchantRelationshipStorageRepository
            ->findExistingPriceProductConcreteMerchantRelationshipEntitiesByProductIds($productIds);

        $this->write($priceProductMerchantRelationshipStorageTransfers, $existingStorageEntities);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[] $priceProductMerchantRelationshipStorageTransfers
     * @param \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage[] $existingStorageEntities
     * @param bool $mergePrices
     *
     * @return void
     */
    protected function write(
        array $priceProductMerchantRelationshipStorageTransfers,
        array $existingStorageEntities = [],
        bool $mergePrices = false
    ): void {
        $existingStorageEntities = $this->mapStorageEntitiesByPriceKey($existingStorageEntities);

        foreach ($priceProductMerchantRelationshipStorageTransfers as $priceProductMerchantRelationshipStorageTransfer) {
            $existingPriceProductConcreteMerchantRelationshipStorageEntity = $existingStorageEntities[$priceProductMerchantRelationshipStorageTransfer->getPriceKey()] ?? null;

            $priceProductMerchantRelationshipStorageTransfer = $this->priceGrouper->groupAndMergePricesData(
                $priceProductMerchantRelationshipStorageTransfer,
                $mergePrices && $existingPriceProductConcreteMerchantRelationshipStorageEntity ? $existingPriceProductConcreteMerchantRelationshipStorageEntity->getData() : []
            );

            if (empty($priceProductMerchantRelationshipStorageTransfer->getPrices())) { // Skip it, should be deleted
                continue;
            }

            unset($existingStorageEntities[$priceProductMerchantRelationshipStorageTransfer->getPriceKey()]);
            if ($existingPriceProductConcreteMerchantRelationshipStorageEntity) {
                $this->priceProductMerchantRelationshipStorageEntityManager->updatePriceProductConcrete(
                    $priceProductMerchantRelationshipStorageTransfer,
                    $existingPriceProductConcreteMerchantRelationshipStorageEntity
                );

                continue;
            }

            $this->priceProductMerchantRelationshipStorageEntityManager->createPriceProductConcrete(
                $priceProductMerchantRelationshipStorageTransfer
            );
        }

        // Delete the rest of the entities
        $this->priceProductMerchantRelationshipStorageEntityManager
            ->deletePriceProductConcreteEntities($existingStorageEntities);
    }

    /**
     * @param \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage[] $priceProductMerchantRelationshipStorageEntities
     *
     * @return array
     */
    protected function mapStorageEntitiesByPriceKey(array $priceProductMerchantRelationshipStorageEntities): array
    {
        $mappedPriceProductConcreteMerchantRelationshipStorageEntities = [];
        foreach ($priceProductMerchantRelationshipStorageEntities as $priceProductMerchantRelationshipStorageEntity) {
            $mappedPriceProductConcreteMerchantRelationshipStorageEntities[$priceProductMerchantRelationshipStorageEntity->getPriceKey()] = $priceProductMerchantRelationshipStorageEntity;
        }

        return $mappedPriceProductConcreteMerchantRelationshipStorageEntities;
    }
}
