<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryResourceAliasStorage\Business\ProductCategoryStorage;

use Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorage;
use Spryker\Zed\ProductCategoryResourceAliasStorage\Persistence\ProductCategoryResourceAliasStorageEntityManagerInterface;
use Spryker\Zed\ProductCategoryResourceAliasStorage\Persistence\ProductCategoryResourceAliasStorageRepositoryInterface;

class ProductAbstractCategoryStorageWriter implements ProductAbstractCategoryStorageWriterInterface
{
    protected const KEY_SKU = 'sku';

    /**
     * @var \Spryker\Zed\ProductCategoryResourceAliasStorage\Persistence\ProductCategoryResourceAliasStorageRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\ProductCategoryResourceAliasStorage\Persistence\ProductCategoryResourceAliasStorageEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\ProductCategoryResourceAliasStorage\Persistence\ProductCategoryResourceAliasStorageRepositoryInterface $repository
     * @param \Spryker\Zed\ProductCategoryResourceAliasStorage\Persistence\ProductCategoryResourceAliasStorageEntityManagerInterface $entityManager
     */
    public function __construct(
        ProductCategoryResourceAliasStorageRepositoryInterface $repository,
        ProductCategoryResourceAliasStorageEntityManagerInterface $entityManager
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param int[] $productAbstractCategoryIds
     *
     * @return void
     */
    public function updateProductAbstractCategoryStorageSkus(array $productAbstractCategoryIds): void
    {
        $productCategoryData = $this->repository->getProductAbstractCategorysSkuList($productAbstractCategoryIds);
        $productAbstractCategoryStorageEntities = $this->repository->getProductAbstractCategoryStorageEntities(array_keys($productCategoryData));

        foreach ($productAbstractCategoryStorageEntities as $productAbstractCategoryStorageEntity) {
            $sku = $productCategoryData[$productAbstractCategoryStorageEntity->getFkProductAbstract()][static::KEY_SKU];
            $oldSku = $productAbstractCategoryStorageEntity->getSku();

            if ($oldSku === $sku) {
                continue;
            }

            if (!empty($oldSku)) {
                $this->unpublishProductCategoryStorageMappingResource($productAbstractCategoryStorageEntity);
            }

            $productAbstractCategoryStorageEntity->setSku($sku);
            $this->entityManager->saveProductAbstractCategoryStorageEntity($productAbstractCategoryStorageEntity);
        }
    }

    /**
     * @param \Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorage $productAbstractCategoryStorage
     *
     * @return void
     */
    protected function unpublishProductCategoryStorageMappingResource(SpyProductAbstractCategoryStorage $productAbstractCategoryStorage): void
    {
        $productAbstractCategoryStorage->syncUnpublishedMessageForMappingResource();
    }
}
