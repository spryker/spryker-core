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
        $this->publishByBusinessUnits(array_keys($businessUnitProducts));
    }

    /**
     * @param int[] $companyBusinessUnitIds
     *
     * @return void
     */
    public function publishByBusinessUnits(array $companyBusinessUnitIds): void
    {
        $priceProductMerchantRelationshipStorageTransfers = $this->priceProductMerchantRelationshipStorageRepository
            ->getProductConcretePriceDataByCompanyBusinessUnitIds($companyBusinessUnitIds);

        $existingPriceProductMerchantRelationshipStorageEntities = $this->priceProductMerchantRelationshipStorageRepository
            ->findExistingPriceProductConcreteMerchantRelationshipStorageEntitiesByCompanyBusinessUnitIds($companyBusinessUnitIds);

        $mappedPriceProductMerchantRelationshipStorageEntities = $this->mapPriceProductMerchantRelationshipStorageEntitiesByKey($existingPriceProductMerchantRelationshipStorageEntities);
        unset($existingPriceProductMerchantRelationshipStorageEntities);

        $this->write($priceProductMerchantRelationshipStorageTransfers, $mappedPriceProductMerchantRelationshipStorageEntities);
    }

    /**
     * @param int[] $priceProductMerchantRelationshipIds
     *
     * @return void
     */
    public function publishConcretePriceProductMerchantRelationship(array $priceProductMerchantRelationshipIds): void
    {
        $priceProductMerchantRelationshipStorageTransfers = $this->priceProductMerchantRelationshipStorageRepository
            ->findMerchantRelationshipProductConcretePricesStorageByIds($priceProductMerchantRelationshipIds);

        $priceKeys = array_map(function (PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer) {
            return $priceProductMerchantRelationshipStorageTransfer->getPriceKey();
        }, $priceProductMerchantRelationshipStorageTransfers);

        $existingPriceProductMerchantRelationshipStorageEntities = $this->priceProductMerchantRelationshipStorageRepository
            ->findExistingPriceProductConcreteMerchantRelationshipStorageEntitiesByPriceKeys($priceKeys);

        $mappedPriceProductMerchantRelationshipStorageEntities = $this->mapPriceProductMerchantRelationshipStorageEntitiesByKey($existingPriceProductMerchantRelationshipStorageEntities);
        unset($existingPriceProductMerchantRelationshipStorageEntities);

        $this->write($priceProductMerchantRelationshipStorageTransfers, $mappedPriceProductMerchantRelationshipStorageEntities);
    }

    /**
     * @param int[] $priceProductMerchantRelationshipIds
     *
     * @return void
     */
    public function unpublishConcretePriceProductMerchantRelationship(array $priceProductMerchantRelationshipIds): void
    {
        $priceProductMerchantRelationshipStorageTransfers = $this->priceProductMerchantRelationshipStorageRepository
            ->findMerchantRelationshipProductConcretePricesStorageByIds($priceProductMerchantRelationshipIds);

        $priceKeys = array_map(function (PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer) {
            return $priceProductMerchantRelationshipStorageTransfer->getPriceKey();
        }, $priceProductMerchantRelationshipStorageTransfers);

        $existingPriceProductMerchantRelationshipStorageEntities = $this->priceProductMerchantRelationshipStorageRepository
            ->findExistingPriceProductConcreteMerchantRelationshipStorageEntitiesByPriceKeys($priceKeys);

        foreach ($existingPriceProductMerchantRelationshipStorageEntities as $priceProductMerchantRelationshipStorageEntity) {
            $priceProductMerchantRelationshipStorageEntity->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[] $priceProductMerchantRelationshipStorageTransfers
     * @param \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage[] $mappedPriceProductMerchantRelationshipStorageEntities
     *
     * @return void
     */
    protected function write(array $priceProductMerchantRelationshipStorageTransfers, array $mappedPriceProductMerchantRelationshipStorageEntities = []): void
    {
        $priceProductMerchantRelationshipStorageTransfers = $this->priceGrouper->groupPrices(
            $priceProductMerchantRelationshipStorageTransfers
        );

        foreach ($priceProductMerchantRelationshipStorageTransfers as $merchantRelationshipStorageTransfer) {
            if (isset($mappedPriceProductMerchantRelationshipStorageEntities[$merchantRelationshipStorageTransfer->getPriceKey()])) {
                $this->priceProductMerchantRelationshipStorageEntityManager->updatePriceProductConcrete(
                    $mappedPriceProductMerchantRelationshipStorageEntities[$merchantRelationshipStorageTransfer->getPriceKey()],
                    $merchantRelationshipStorageTransfer
                );

                unset($mappedPriceProductMerchantRelationshipStorageEntities[$merchantRelationshipStorageTransfer->getPriceKey()]);
                continue;
            }

            $this->priceProductMerchantRelationshipStorageEntityManager->createPriceProductConcrete(
                $merchantRelationshipStorageTransfer
            );
        }

        // Delete the rest of the entites
        foreach ($mappedPriceProductMerchantRelationshipStorageEntities as $priceProductMerchantRelationshipStorageEntity) {
            $priceProductMerchantRelationshipStorageEntity->delete();
        }
    }

    /**
     * @param \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage[] $priceProductMerchantRelationshipStorageEntities
     *
     * @return array
     */
    protected function mapPriceProductMerchantRelationshipStorageEntitiesByKey(array $priceProductMerchantRelationshipStorageEntities)
    {
        $mappedPriceProductMerchantRelationshipStorageEntities = [];
        foreach ($priceProductMerchantRelationshipStorageEntities as $priceProductMerchantRelationshipStorageEntity) {
            $mappedPriceProductMerchantRelationshipStorageEntities[$priceProductMerchantRelationshipStorageEntity->getPriceKey()] = $priceProductMerchantRelationshipStorageEntity;
        }

        return $mappedPriceProductMerchantRelationshipStorageEntities;
    }
}
