<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model;

use Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageEntityManagerInterface;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageRepositoryInterface;

class PriceProductConcreteStorageWriter implements PriceProductConcreteStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageEntityManagerInterface
     */
    protected $priceProductMerchantRelationshipStorageEntityManager;

    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageRepositoryInterface
     */
    protected $priceProductMerchantRelationshipStorageRepository;

    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model\PriceGrouperInterface
     */
    protected $priceGrouper;

    /**
     * @param \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageEntityManagerInterface $priceProductMerchantRelationshipStorageEntityManager
     * @param \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageRepositoryInterface $priceProductMerchantRelationshipStorageRepository
     * @param \Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model\PriceGrouperInterface $priceGrouper
     */
    public function __construct(
        PriceProductMerchantRelationshipStorageEntityManagerInterface $priceProductMerchantRelationshipStorageEntityManager,
        PriceProductMerchantRelationshipStorageRepositoryInterface $priceProductMerchantRelationshipStorageRepository,
        PriceGrouperInterface $priceGrouper
    ) {
        $this->priceProductMerchantRelationshipStorageEntityManager = $priceProductMerchantRelationshipStorageEntityManager;
        $this->priceProductMerchantRelationshipStorageRepository = $priceProductMerchantRelationshipStorageRepository;
        $this->priceGrouper = $priceGrouper;
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
     * @param int[] $companyBusinessUnitIds
     *
     * @return void
     */
    public function publishByCompanyBusinessUnitIds(array $companyBusinessUnitIds): void
    {
        $priceProductMerchantRelationshipStorageTransfers = $this->priceProductMerchantRelationshipStorageRepository
            ->findProductConcretePriceDataByCompanyBusinessUnitIds($companyBusinessUnitIds);

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
            ->findMerchantRelationshipProductConcretePricesByIds($priceProductMerchantRelationshipIds);

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
            ->findMerchantRelationshipProductConcretePriceDataByProductIds($productIds);

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
            $priceProductMerchantRelationshipStorageTransfer = $this->priceGrouper->groupPrices(
                $priceProductMerchantRelationshipStorageTransfer
            );

            if (isset($existingStorageEntities[$priceProductMerchantRelationshipStorageTransfer->getPriceKey()])) {
                $this->priceProductMerchantRelationshipStorageEntityManager->updatePriceProductConcrete(
                    $priceProductMerchantRelationshipStorageTransfer,
                    $existingStorageEntities[$priceProductMerchantRelationshipStorageTransfer->getPriceKey()],
                    $mergePrices
                );

                unset($existingStorageEntities[$priceProductMerchantRelationshipStorageTransfer->getPriceKey()]);
                continue;
            }

            $this->priceProductMerchantRelationshipStorageEntityManager->createPriceProductConcrete(
                $priceProductMerchantRelationshipStorageTransfer
            );

            unset($existingStorageEntities[$priceProductMerchantRelationshipStorageTransfer->getPriceKey()]);
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
