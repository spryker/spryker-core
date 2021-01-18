<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer;
use Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorage;
use Propel\Runtime\Collection\ObjectCollection;

class ProductCategoryStorageMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorage[] $productAbstractCategoryStorageEntities
     * @param \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer[][][] $productAbstractCategoryStorageTransfers
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer[][][]
     */
    public function mapProductAbstractCategoryStorageEntitiesToProductAbstractCategoryStorageTransfers(
        ObjectCollection $productAbstractCategoryStorageEntities,
        array $productAbstractCategoryStorageTransfers
    ): array {
        foreach ($productAbstractCategoryStorageEntities as $productAbstractCategoryStorageEntity) {
            $idProductAbstract = $productAbstractCategoryStorageEntity->getFkProductAbstract();
            $locale = $productAbstractCategoryStorageEntity->getLocale();
            $store = $productAbstractCategoryStorageEntity->getStore();

            $productAbstractCategoryStorageTransfers[$idProductAbstract][$store][$locale] = (new ProductAbstractCategoryStorageTransfer())
                ->fromArray($productAbstractCategoryStorageEntity->getData(), true);
        }

        return $productAbstractCategoryStorageTransfers;
    }

    /**
     * @param int $idProductAbstract
     * @param string $storeName
     * @param string $localeName
     * @param \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer $productAbstractCategoryStorageTransfer
     *
     * @return \Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorage
     */
    public function mapProductAbstractCategoryStorageEntity(
        int $idProductAbstract,
        string $storeName,
        string $localeName,
        ProductAbstractCategoryStorageTransfer $productAbstractCategoryStorageTransfer
    ): SpyProductAbstractCategoryStorage {
        return (new SpyProductAbstractCategoryStorage())
            ->setFkProductAbstract($idProductAbstract)
            ->setStore($storeName)
            ->setLocale($localeName)
            ->setData($productAbstractCategoryStorageTransfer->toArray());
    }
}
