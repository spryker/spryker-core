<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityResourceAliasStorage\Business\ProductAvailabilityStorage;

use Orm\Zed\AvailabilityStorage\Persistence\SpyAvailabilityStorage;
use Spryker\Zed\AvailabilityResourceAliasStorage\Persistence\AvailabilityResourceAliasStorageEntityManagerInterface;
use Spryker\Zed\AvailabilityResourceAliasStorage\Persistence\AvailabilityResourceAliasStorageRepositoryInterface;

class AvailabilityWriter implements AvailabilityWriterInterface
{
    protected const KEY_SKU = 'sku';

    /**
     * @var \Spryker\Zed\AvailabilityResourceAliasStorage\Persistence\AvailabilityResourceAliasStorageRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\AvailabilityResourceAliasStorage\Persistence\AvailabilityResourceAliasStorageEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\AvailabilityResourceAliasStorage\Persistence\AvailabilityResourceAliasStorageRepositoryInterface $repository
     * @param \Spryker\Zed\AvailabilityResourceAliasStorage\Persistence\AvailabilityResourceAliasStorageEntityManagerInterface $entityManager
     */
    public function __construct(
        AvailabilityResourceAliasStorageRepositoryInterface $repository,
        AvailabilityResourceAliasStorageEntityManagerInterface $entityManager
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param int[] $availabilityIds
     *
     * @return void
     */
    public function updateAvailabilityStorageSkus(array $availabilityIds): void
    {
        $availabilityStorageEntities = $this->repository->getAvailabilityStorageEntities($availabilityIds);

        $productAbstractData = $this->repository->getProductAbstractSkuList($availabilityIds);

        foreach ($availabilityStorageEntities as $availabilityStorageEntity) {
            $sku = $productAbstractData[$availabilityStorageEntity->getFkProductAbstract()][static::KEY_SKU];
            $oldSku = $availabilityStorageEntity->getSku();
            if ($oldSku === $sku) {
                continue;
            }
            if (!empty($oldSku)) {
                $this->unpublishAvailabilityStorageMappingResource($availabilityStorageEntity);
            }

            $availabilityStorageEntity->setSku($sku);
            $this->entityManager->saveAvailabilityStorageEntity($availabilityStorageEntity);
        }
    }

    /**
     * @param \Orm\Zed\AvailabilityStorage\Persistence\SpyAvailabilityStorage $availabilityStorageEntity
     *
     * @return void
     */
    protected function unpublishAvailabilityStorageMappingResource(SpyAvailabilityStorage $availabilityStorageEntity): void
    {
        $availabilityStorageEntity->syncUnpublishedMessageForMappingResource();
    }
}
