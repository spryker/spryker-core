<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductResourceAliasStorage\Business\ProductStorage;

use Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorage;
use Spryker\Zed\ProductResourceAliasStorage\Persistence\ProductResourceAliasStorageEntityManagerInterface;
use Spryker\Zed\ProductResourceAliasStorage\Persistence\ProductResourceAliasStorageRepositoryInterface;

class ProductConcreteStorageWriter implements ProductConcreteStorageWriterInterface
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
     * @param \Spryker\Zed\ProductResourceAliasStorage\Persistence\ProductResourceAliasStorageRepositoryInterface $repository
     * @param \Spryker\Zed\ProductResourceAliasStorage\Persistence\ProductResourceAliasStorageEntityManagerInterface $entityManager
     */
    public function __construct(
        ProductResourceAliasStorageRepositoryInterface $repository,
        ProductResourceAliasStorageEntityManagerInterface $entityManager
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function updateProductConcreteStorageSkus(array $productConcreteIds): void
    {
        $productConcreteStorageEntities = $this->repository->getProductConcreteStorageEntities($productConcreteIds);

        $productConcreteData = $this->repository->getProductConcreteSkuList($productConcreteIds);

        foreach ($productConcreteStorageEntities as $productConcreteStorageEntity) {
            $sku = $productConcreteData[$productConcreteStorageEntity->getFkProduct()][static::KEY_SKU];

            $oldSku = $productConcreteStorageEntity->getSku();
            if ($oldSku === $sku) {
                continue;
            }
            if (!empty($oldSku)) {
                $this->unpublishProductStorageMappingResource($productConcreteStorageEntity);
            }

            $productConcreteStorageEntity->setSku($sku);
            $this->entityManager->saveProductConcreteStorageEntity($productConcreteStorageEntity);
        }
    }

    /**
     * @param \Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorage $productConcreteStorageEntity
     *
     * @return void
     */
    protected function unpublishProductStorageMappingResource(SpyProductConcreteStorage $productConcreteStorageEntity): void
    {
        $productConcreteStorageEntity->syncUnpublishedMessageForMappingResource();
    }
}
