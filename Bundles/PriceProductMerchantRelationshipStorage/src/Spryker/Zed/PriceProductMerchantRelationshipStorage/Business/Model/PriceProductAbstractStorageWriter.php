<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model;

use Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageEntityManagerInterface;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageRepositoryInterface;

class PriceProductAbstractStorageWriter implements PriceProductAbstractStorageWriterInterface
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
     * @param int[] $companyBusinessUnitIds
     *
     * @return void
     */
    public function publishByCompanyBusinessUnitIds(array $companyBusinessUnitIds): void
    {
        $priceProductMerchantRelationshipStorageTransfers = $this->priceProductMerchantRelationshipStorageRepository
            ->getProductAbstractPriceDataByCompanyBusinessUnitIds($companyBusinessUnitIds);

        $existingPriceProductMerchantRelationshipStorageEntities = $this->priceProductMerchantRelationshipStorageRepository
            ->findExistingPriceProductAbstractMerchantRelationshipStorageEntitiesByCompanyBusinessUnitIds($companyBusinessUnitIds);

        $mappedPriceProductMerchantRelationshipStorageEntities = $this->mapPriceProductMerchantRelationshipStorageEntitiesByKey($existingPriceProductMerchantRelationshipStorageEntities);
        unset($existingPriceProductMerchantRelationshipStorageEntities);

        $this->write($priceProductMerchantRelationshipStorageTransfers, $mappedPriceProductMerchantRelationshipStorageEntities);
    }

    /**
     * @param int[] $priceProductMerchantRelationshipIds
     *
     * @return void
     */
    public function publishAbstractPriceProductMerchantRelationship(array $priceProductMerchantRelationshipIds): void
    {
        $priceProductMerchantRelationshipStorageTransfers = $this->priceProductMerchantRelationshipStorageRepository
            ->findMerchantRelationshipProductAbstractPricesStorageByIds($priceProductMerchantRelationshipIds);

        $priceKeys = array_map(function (PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer) {
            return $priceProductMerchantRelationshipStorageTransfer->getPriceKey();
        }, $priceProductMerchantRelationshipStorageTransfers);

        $existingPriceProductMerchantRelationshipStorageEntities = $this->priceProductMerchantRelationshipStorageRepository
            ->findExistingPriceProductAbstractMerchantRelationshipStorageEntitiesByPriceKeys($priceKeys);

        $mappedPriceProductMerchantRelationshipStorageEntities = $this->mapPriceProductMerchantRelationshipStorageEntitiesByKey($existingPriceProductMerchantRelationshipStorageEntities);
        unset($existingPriceProductMerchantRelationshipStorageEntities);

        $this->write($priceProductMerchantRelationshipStorageTransfers, $mappedPriceProductMerchantRelationshipStorageEntities);
    }

    /**
     * @param int[] $priceProductMerchantRelationshipIds
     *
     * @return void
     */
    public function unpublishAbstractPriceProductMerchantRelationship(array $priceProductMerchantRelationshipIds): void
    {
        $priceProductMerchantRelationshipStorageTransfers = $this->priceProductMerchantRelationshipStorageRepository
            ->findMerchantRelationshipProductAbstractPricesStorageByIds($priceProductMerchantRelationshipIds);

        $priceKeys = array_map(function (PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer) {
            return $priceProductMerchantRelationshipStorageTransfer->getPriceKey();
        }, $priceProductMerchantRelationshipStorageTransfers);

        $existingPriceProductMerchantRelationshipStorageEntities = $this->priceProductMerchantRelationshipStorageRepository
            ->findExistingPriceProductAbstractMerchantRelationshipStorageEntitiesByPriceKeys($priceKeys);

        foreach ($existingPriceProductMerchantRelationshipStorageEntities as $priceProductMerchantRelationshipStorageEntity) {
            $priceProductMerchantRelationshipStorageEntity->delete();
        }
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
     * @param \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage[] $mappedPriceProductMerchantRelationshipStorageEntities
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
                $this->priceProductMerchantRelationshipStorageEntityManager->updatePriceProductAbstract(
                    $mappedPriceProductMerchantRelationshipStorageEntities[$merchantRelationshipStorageTransfer->getPriceKey()],
                    $merchantRelationshipStorageTransfer
                );

                unset($mappedPriceProductMerchantRelationshipStorageEntities[$merchantRelationshipStorageTransfer->getPriceKey()]);
                continue;
            }

            $this->priceProductMerchantRelationshipStorageEntityManager->createPriceProductAbstract(
                $merchantRelationshipStorageTransfer
            );
        }

        // Delete the rest of the entites
        foreach ($mappedPriceProductMerchantRelationshipStorageEntities as $priceProductMerchantRelationshipStorageEntity) {
            $priceProductMerchantRelationshipStorageEntity->delete();
        }

        unset($mappedPriceProductMerchantRelationshipStorageEntities);
    }

    /**
     * @param \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage[] $priceProductMerchantRelationshipStorageEntities
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
