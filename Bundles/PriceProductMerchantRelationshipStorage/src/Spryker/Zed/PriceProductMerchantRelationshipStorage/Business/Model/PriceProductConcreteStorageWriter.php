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
     * @param array<mixed> $businessUnitProducts
     *
     * @return void
     */
    public function publishByBusinessUnitProducts(array $businessUnitProducts): void
    {
        $this->publishByCompanyBusinessUnitIds(array_keys($businessUnitProducts));
    }

    /**
     * @param array<int> $companyBusinessUnitIds
     *
     * @return void
     */
    public function publishByCompanyBusinessUnitIds(array $companyBusinessUnitIds): void
    {
        $priceProductMerchantRelationshipStorageTransfers = $this->priceProductMerchantRelationshipStorageRepository
            ->findMerchantRelationshipProductConcretePricesDataByCompanyBusinessUnitIds($companyBusinessUnitIds);

        $existingStorageEntities = $this->priceProductMerchantRelationshipStorageRepository
            ->findExistingPriceProductConcreteMerchantRelationshipEntitiesByCompanyBusinessUnitIds($companyBusinessUnitIds);

        $this->write($priceProductMerchantRelationshipStorageTransfers, $existingStorageEntities);
    }

    /**
     * @param array<int> $priceProductMerchantRelationshipIds
     *
     * @return void
     */
    public function publishConcretePriceProductMerchantRelationship(array $priceProductMerchantRelationshipIds): void
    {
        $priceProductMerchantRelationshipStorageTransfers = $this->priceProductMerchantRelationshipStorageRepository
            ->findMerchantRelationshipProductConcretePricesDataByIds($priceProductMerchantRelationshipIds);

        $priceKeys = array_map(function (PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer) {
            return $priceProductMerchantRelationshipStorageTransfer->getPriceKey();
        }, $priceProductMerchantRelationshipStorageTransfers);

        $existingStorageEntities = $this->priceProductMerchantRelationshipStorageRepository
            ->findExistingPriceProductConcreteMerchantRelationshipEntitiesByPriceKeys($priceKeys);

        $this->write($priceProductMerchantRelationshipStorageTransfers, $existingStorageEntities, true);
    }

    /**
     * @param array<int> $productIds
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
     * @param array<\Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer> $priceProductMerchantRelationshipStorageTransfers
     * @param array<\Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage> $existingStorageEntities
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
        $priceProductMerchantRelationshipStorageTransfers = $this->executePriceProductMerchantRelationshipStorageFilterPlugins($priceProductMerchantRelationshipStorageTransfers);

        foreach ($priceProductMerchantRelationshipStorageTransfers as $priceProductMerchantRelationshipStorageTransfer) {
            $priceProductMerchantRelationshipStorageTransfer = $this->groupPrices(
                $priceProductMerchantRelationshipStorageTransfer,
                $existingStorageEntities,
                $mergePrices,
            );

            // Skip if no prices, the price entity will be deleted at the end
            if (empty($priceProductMerchantRelationshipStorageTransfer->getPrices())) {
                continue;
            }

            if (isset($existingStorageEntities[$priceProductMerchantRelationshipStorageTransfer->getPriceKey()])) {
                $this->priceProductMerchantRelationshipStorageEntityManager->updatePriceProductConcrete(
                    $priceProductMerchantRelationshipStorageTransfer,
                    $existingStorageEntities[$priceProductMerchantRelationshipStorageTransfer->getPriceKey()],
                );

                unset($existingStorageEntities[$priceProductMerchantRelationshipStorageTransfer->getPriceKey()]);

                continue;
            }

            $this->priceProductMerchantRelationshipStorageEntityManager->createPriceProductConcrete(
                $priceProductMerchantRelationshipStorageTransfer,
            );

            unset($existingStorageEntities[$priceProductMerchantRelationshipStorageTransfer->getPriceKey()]);
        }

        // Delete the rest of the entities
        $this->priceProductMerchantRelationshipStorageEntityManager
            ->deletePriceProductConcreteEntities($existingStorageEntities);
    }

    /**
     * @param array<\Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage> $priceProductMerchantRelationshipStorageEntities
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

    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
     * @param array<\Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage> $existingStorageEntities
     * @param bool $mergePrices
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer
     */
    protected function groupPrices(
        PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer,
        array $existingStorageEntities = [],
        bool $mergePrices = false
    ): PriceProductMerchantRelationshipStorageTransfer {
        $priceProductMerchantRelationshipStorageTransfer = $this->priceGrouper->groupPricesData($priceProductMerchantRelationshipStorageTransfer);

        if (!$mergePrices) {
            return $priceProductMerchantRelationshipStorageTransfer;
        }

        return $this->priceGrouper->groupPricesData(
            $priceProductMerchantRelationshipStorageTransfer,
            $this->getExistingPricesDataForPriceKey($existingStorageEntities, $priceProductMerchantRelationshipStorageTransfer->getPriceKey()),
        );
    }

    /**
     * @param array<\Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage> $existingStorageEntities
     * @param string $priceKey
     *
     * @return array
     */
    protected function getExistingPricesDataForPriceKey(array $existingStorageEntities, string $priceKey): array
    {
        if (isset($existingStorageEntities[$priceKey])) {
            return $existingStorageEntities[$priceKey]->getData();
        }

        return [];
    }
}
