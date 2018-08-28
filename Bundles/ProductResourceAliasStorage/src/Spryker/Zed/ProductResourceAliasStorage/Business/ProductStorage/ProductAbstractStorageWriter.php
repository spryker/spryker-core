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
    protected $productResourceAliasStorageRepository;

    /**
     * @var \Spryker\Zed\ProductResourceAliasStorage\Persistence\ProductResourceAliasStorageEntityManagerInterface
     */
    protected $productResourceAliasStorageEntityManager;

    /**
     * @param \Spryker\Zed\ProductResourceAliasStorage\Persistence\ProductResourceAliasStorageRepositoryInterface $productResourceAliasStorageRepository
     * @param \Spryker\Zed\ProductResourceAliasStorage\Persistence\ProductResourceAliasStorageEntityManagerInterface $productResourceAliasStorageEntityManager
     */
    public function __construct(
        ProductResourceAliasStorageRepositoryInterface $productResourceAliasStorageRepository,
        ProductResourceAliasStorageEntityManagerInterface $productResourceAliasStorageEntityManager
    ) {
        $this->productResourceAliasStorageRepository = $productResourceAliasStorageRepository;
        $this->productResourceAliasStorageEntityManager = $productResourceAliasStorageEntityManager;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function updateProductAbstractStorageSkus(array $productAbstractIds): void
    {
        $productAbstractStorageEntities = $this->productResourceAliasStorageRepository->getProductAbstractStorageEntities($productAbstractIds);

        $productAbstractData = $this->productResourceAliasStorageRepository->getProductAbstractSkuList($productAbstractIds);

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
            $this->productResourceAliasStorageEntityManager->saveProductAbstractStorageEntity($productAbstractStorageEntity);
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
