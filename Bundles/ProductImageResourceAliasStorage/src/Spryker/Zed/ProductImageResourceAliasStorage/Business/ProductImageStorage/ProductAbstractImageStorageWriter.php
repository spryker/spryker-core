<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageResourceAliasStorage\Business\ProductImageStorage;

use Orm\Zed\ProductImageStorage\Persistence\SpyProductAbstractImageStorage;
use Spryker\Zed\ProductImageResourceAliasStorage\Persistence\ProductImageResourceAliasStorageEntityManagerInterface;
use Spryker\Zed\ProductImageResourceAliasStorage\Persistence\ProductImageResourceAliasStorageRepositoryInterface;

class ProductAbstractImageStorageWriter implements ProductAbstractImageStorageWriterInterface
{
    protected const KEY_SKU = 'sku';

    /**
     * @var \Spryker\Zed\ProductImageResourceAliasStorage\Persistence\ProductImageResourceAliasStorageRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\ProductImageResourceAliasStorage\Persistence\ProductImageResourceAliasStorageEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\ProductImageResourceAliasStorage\Persistence\ProductImageResourceAliasStorageRepositoryInterface $repository
     * @param \Spryker\Zed\ProductImageResourceAliasStorage\Persistence\ProductImageResourceAliasStorageEntityManagerInterface $entityManager
     */
    public function __construct(
        ProductImageResourceAliasStorageRepositoryInterface $repository,
        ProductImageResourceAliasStorageEntityManagerInterface $entityManager
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param int[] $productAbstractImageSetIds
     *
     * @return void
     */
    public function updateProductAbstractImageStorageSkus(array $productAbstractImageSetIds): void
    {
        $productImageSetData = $this->repository->getProductAbstractImageSetsSkuList($productAbstractImageSetIds);
        $productAbstractImageStorageEntities = $this->repository->getProductAbstractImageStorageEntities(array_keys($productImageSetData));

        foreach ($productAbstractImageStorageEntities as $productAbstractImageStorageEntity) {
            $sku = $productImageSetData[$productAbstractImageStorageEntity->getFkProductAbstract()][static::KEY_SKU];
            $oldSku = $productAbstractImageStorageEntity->getSku();
            if ($oldSku === $sku) {
                continue;
            }
            if (!empty($oldSku)) {
                $this->unpublishProductImageStorageMappingResource($productAbstractImageStorageEntity);
            }

            $productAbstractImageStorageEntity->setSku($sku);
            $this->entityManager->saveProductAbstractImageStorageEntity($productAbstractImageStorageEntity);
        }
    }

    /**
     * @param \Orm\Zed\ProductImageStorage\Persistence\SpyProductAbstractImageStorage $productAbstractImageStorage
     *
     * @return void
     */
    protected function unpublishProductImageStorageMappingResource(SpyProductAbstractImageStorage $productAbstractImageStorage): void
    {
        $productAbstractImageStorage->syncUnpublishedMessageForMappingResource();
    }
}
