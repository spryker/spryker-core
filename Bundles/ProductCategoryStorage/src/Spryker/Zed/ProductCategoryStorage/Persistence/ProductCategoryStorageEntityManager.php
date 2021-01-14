<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Persistence;

use Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer;
use Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorage;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStoragePersistenceFactory getFactory()
 */
class ProductCategoryStorageEntityManager extends AbstractEntityManager implements ProductCategoryStorageEntityManagerInterface
{
    /**
     * @param string[] $keys
     *
     * @return void
     */
    public function deleteProductAbstractCategoryStorages(array $keys): void
    {
        $productAbstractCategoryStorageEntities = $this->getFactory()
            ->createProductAbstractCategoryStoragePropelQuery()
            ->filterByKey_In($keys)
            ->find();

        foreach ($productAbstractCategoryStorageEntities as $productAbstractCategoryStorageEntity) {
            $productAbstractCategoryStorageEntity->delete();
        }
    }

    /**
     * @param int $idProductAbstract
     * @param string $storeName
     * @param string $localeName
     *
     * @return void
     */
    public function deleteProductAbstractCategoryStorage(int $idProductAbstract, string $storeName, string $localeName): void
    {
        $productAbstractCategoryStorageEntity = $this->getFactory()
            ->createProductAbstractCategoryStoragePropelQuery()
            ->filterByFkProductAbstract($idProductAbstract)
            ->filterByStore($storeName)
            ->filterByLocale($localeName)
            ->findOne();

        if (!$productAbstractCategoryStorageEntity) {
            return;
        }

        $productAbstractCategoryStorageEntity->delete();
    }

    /**
     * @param int $idProductAbstract
     * @param string $storeName
     * @param string $localeName
     * @param \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer $productAbstractCategoryStorageTransfer
     *
     * @return void
     */
    public function saveProductAbstractCategoryStorage(
        int $idProductAbstract,
        string $storeName,
        string $localeName,
        ProductAbstractCategoryStorageTransfer $productAbstractCategoryStorageTransfer
    ): void {
        $productAbstractCategoryStorageEntity = (new SpyProductAbstractCategoryStorage())
            ->setFkProductAbstract($idProductAbstract)
            ->setStore($storeName)
            ->setLocale($localeName)
            ->setData($productAbstractCategoryStorageTransfer->toArray());

        $productAbstractCategoryStorageEntity->save();
    }
}
