<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model;

use Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer;

class PriceProductAbstractStorageWriter extends AbstractPriceProductMerchantRelationshipStorageWriter implements PriceProductAbstractStorageWriterInterface
{
    /**
     * @param int[] $companyBusinessUnitIds
     *
     * @return void
     */
    public function publishByCompanyBusinessUnitIds(array $companyBusinessUnitIds): void
    {
        $priceProductMerchantRelationshipStorageTransfers = $this->priceProductMerchantRelationshipStorageRepository
            ->findMerchantRelationshipProductAbstractPricesDataByCompanyBusinessUnitIds($companyBusinessUnitIds);

        $existingStorageEntities = $this->priceProductMerchantRelationshipStorageRepository
            ->findExistingPriceProductAbstractMerchantRelationshipEntitiesByCompanyBusinessUnitIds($companyBusinessUnitIds);

        $this->write($priceProductMerchantRelationshipStorageTransfers, $existingStorageEntities);
    }

    /**
     * @param int[] $priceProductMerchantRelationshipIds
     *
     * @return void
     */
    public function publishAbstractPriceProductMerchantRelationship(array $priceProductMerchantRelationshipIds): void
    {
        $priceProductMerchantRelationshipStorageTransfers = $this->priceProductMerchantRelationshipStorageRepository
            ->findMerchantRelationshipProductAbstractPricesByIds($priceProductMerchantRelationshipIds);

        if (empty($priceProductMerchantRelationshipStorageTransfers)) {
            return;
        }

        $priceKeys = array_map(function (PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer) {
            return $priceProductMerchantRelationshipStorageTransfer->getPriceKey();
        }, $priceProductMerchantRelationshipStorageTransfers);

        $existingStorageEntities = $this->priceProductMerchantRelationshipStorageRepository
            ->findExistingPriceProductAbstractMerchantRelationshipEntitiesByPriceKeys($priceKeys);

        $this->write($priceProductMerchantRelationshipStorageTransfers, $existingStorageEntities, true);
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publishAbstractPriceProductByProductAbstractIds(array $productAbstractIds): void
    {
        $priceProductMerchantRelationshipStorageTransfers = $this->priceProductMerchantRelationshipStorageRepository
            ->findMerchantRelationshipProductAbstractPricesDataByProductAbstractIds($productAbstractIds);

        $existingStorageEntities = $this->priceProductMerchantRelationshipStorageRepository
            ->findExistingPriceProductAbstractMerchantRelationshipEntitiesByProductAbstractIds($productAbstractIds);

        $this->write($priceProductMerchantRelationshipStorageTransfers, $existingStorageEntities);
    }

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
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[] $priceProductMerchantRelationshipStorageTransfers
     * @param \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage[] $existingStorageEntities
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
            $priceProductMerchantRelationshipStorageTransfer = $this->priceGrouper->groupPrices(
                $priceProductMerchantRelationshipStorageTransfer
            );

            if (isset($existingStorageEntities[$priceProductMerchantRelationshipStorageTransfer->getPriceKey()])) {
                $this->priceProductMerchantRelationshipStorageEntityManager->updatePriceProductAbstract(
                    $priceProductMerchantRelationshipStorageTransfer,
                    $existingStorageEntities[$priceProductMerchantRelationshipStorageTransfer->getPriceKey()],
                    $mergePrices
                );

                unset($existingStorageEntities[$priceProductMerchantRelationshipStorageTransfer->getPriceKey()]);
                continue;
            }

            $this->priceProductMerchantRelationshipStorageEntityManager->createPriceProductAbstract(
                $priceProductMerchantRelationshipStorageTransfer
            );

            unset($existingStorageEntities[$priceProductMerchantRelationshipStorageTransfer->getPriceKey()]);
        }

        // Delete the rest of the entities
        $this->priceProductMerchantRelationshipStorageEntityManager
            ->deletePriceProductAbstractEntities($existingStorageEntities);
    }

    /**
     * @param \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage[] $priceProductMerchantRelationshipStorageEntities
     *
     * @return array
     */
    protected function mapStorageEntitiesByPriceKey(array $priceProductMerchantRelationshipStorageEntities): array
    {
        $mappedPriceProductAbstractMerchantRelationshipStorageEntities = [];
        foreach ($priceProductMerchantRelationshipStorageEntities as $priceProductMerchantRelationshipStorageEntity) {
            $mappedPriceProductAbstractMerchantRelationshipStorageEntities[$priceProductMerchantRelationshipStorageEntity->getPriceKey()] = $priceProductMerchantRelationshipStorageEntity;
        }

        return $mappedPriceProductAbstractMerchantRelationshipStorageEntities;
    }
}
