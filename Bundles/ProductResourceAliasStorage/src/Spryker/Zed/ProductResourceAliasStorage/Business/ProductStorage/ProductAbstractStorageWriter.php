<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductResourceAliasStorage\Business\ProductStorage;

use Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage;
use Spryker\Zed\ProductResourceAliasStorage\Persistence\ProductResourceAliasStorageEntityManagerInterface;
use Spryker\Zed\ProductResourceAliasStorage\Persistence\ProductResourceAliasStorageRepositoryInterface;

class ProductAbstractStorageWriter implements ProductAbstractStorageWriterInterface
{
    protected const KEY_SKU = 'sku';

    /**
     * @var \Spryker\Zed\ProductResourceAliasStorage\Persistence\ProductResourceAliasStorageRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\ProductResourceAliasStorage\Persistence\ProductResourceAliasStorageEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var bool
     */
    protected $isSendingToQueue;

    /**
     * @param \Spryker\Zed\ProductResourceAliasStorage\Persistence\ProductResourceAliasStorageRepositoryInterface $repository
     * @param \Spryker\Zed\ProductResourceAliasStorage\Persistence\ProductResourceAliasStorageEntityManagerInterface $entityManager
     * @param bool $isSendingToQueue
     */
    public function __construct(
        ProductResourceAliasStorageRepositoryInterface $repository,
        ProductResourceAliasStorageEntityManagerInterface $entityManager,
        bool $isSendingToQueue = true
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function updateProductAbstractStorageSkus(array $productAbstractIds): void
    {
        $productAbstractStorageEntities = $this->repository->getProductAbstractStorageEntities($productAbstractIds);

        $productAbstractData = $this->repository->getProductAbstractSkuList($productAbstractIds);

        foreach ($productAbstractStorageEntities as $productAbstractStorageEntity) {
            $sku = $productAbstractData[$productAbstractStorageEntity->getFkProductAbstract()][static::KEY_SKU];
            $oldSku = $productAbstractStorageEntity->getSku();
            if ($oldSku === $sku) {
                continue;
            }
            if (!empty($oldSku)) {
                $this->unpublishProductStorageMappingResource($productAbstractStorageEntity);
            }

            $productAbstractStorageEntity->setSku($sku);
            $productAbstractStorageEntity->setIsSendingToQueue($this->isSendingToQueue);
            $this->entityManager->saveProductAbstractStorageEntity($productAbstractStorageEntity);
        }
    }

    /**
     * @param \Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage $productAbstractStorageEntity
     *
     * @return void
     */
    protected function unpublishProductStorageMappingResource(SpyProductAbstractStorage $productAbstractStorageEntity): void
    {
        $productAbstractStorageEntity->syncUnpublishedMessageForMappingResource();
    }
}
