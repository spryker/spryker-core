<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageResourceAliasStorage\Business\ProductImageStorage;

use Orm\Zed\ProductImageStorage\Persistence\SpyProductConcreteImageStorage;
use Spryker\Zed\ProductImageResourceAliasStorage\Persistence\ProductImageResourceAliasStorageEntityManagerInterface;
use Spryker\Zed\ProductImageResourceAliasStorage\Persistence\ProductImageResourceAliasStorageRepositoryInterface;

class ProductConcreteImageStorageWriter implements ProductConcreteImageStorageWriterInterface
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
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function updateProductConcreteImageStorageSkus(array $productConcreteIds): void
    {
        $productImageSetData = $this->repository->getProductConcreteImageSetsSkuList($productConcreteIds);
        $productConcreteImageStorageEntities = $this->repository->getProductConcreteImageStorageEntities(array_keys($productImageSetData));

        foreach ($productConcreteImageStorageEntities as $productConcreteImageStorageEntity) {
            $sku = $productImageSetData[$productConcreteImageStorageEntity->getFkProduct()][static::KEY_SKU];
            $oldSku = $productConcreteImageStorageEntity->getSku();
            if ($oldSku === $sku) {
                continue;
            }
            if (!empty($oldSku)) {
                $this->unpublishProductImageStorageMappingResource($productConcreteImageStorageEntity);
            }

            $productConcreteImageStorageEntity->setSku($sku);
            $this->entityManager->saveProductConcreteImageStorageEntity($productConcreteImageStorageEntity);
        }
    }

    /**
     * @param \Orm\Zed\ProductImageStorage\Persistence\SpyProductConcreteImageStorage $productConcreteImageStorage
     *
     * @return void
     */
    protected function unpublishProductImageStorageMappingResource(SpyProductConcreteImageStorage $productConcreteImageStorage): void
    {
        $productConcreteImageStorage->syncUnpublishedMessageForMappingResource();
    }
}
