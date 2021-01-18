<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Persistence;

use Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStoragePersistenceFactory getFactory()
 */
class ProductCategoryStorageEntityManager extends AbstractEntityManager implements ProductCategoryStorageEntityManagerInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function deleteProductAbstractCategoryStorages(array $productAbstractIds): void
    {
        $productAbstractCategoryStorageEntities = $this->getFactory()
            ->createProductAbstractCategoryStoragePropelQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->find();

        if (!$productAbstractCategoryStorageEntities->count()) {
            return;
        }

        $productAbstractCategoryStorageEntities->delete();
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
        $productAbstractCategoryStorageEntity = $this->getFactory()
            ->createProductCategoryStorageMapper()
            ->mapProductAbstractCategoryStorageEntity(
                $idProductAbstract,
                $storeName,
                $localeName,
                $productAbstractCategoryStorageTransfer
            );

        $productAbstractCategoryStorageEntity->save();
    }
}
