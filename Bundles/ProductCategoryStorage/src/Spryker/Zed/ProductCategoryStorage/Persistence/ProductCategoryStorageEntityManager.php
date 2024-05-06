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
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function deleteProductAbstractCategoryStorages(array $productAbstractIds): void
    {
        if ($productAbstractIds === []) {
            return;
        }

        /** @var \Propel\Runtime\Collection\ObjectCollection $productAbstractCategoryStorageCollection */
        $productAbstractCategoryStorageCollection = $this->getFactory()
            ->createProductAbstractCategoryStoragePropelQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->find();

        $productAbstractCategoryStorageCollection->delete();
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
            ->createProductAbstractCategoryStoragePropelQuery()
            ->filterByFkProductAbstract($idProductAbstract)
            ->filterByStore($storeName)
            ->filterByLocale($localeName)
            ->findOneOrCreate();

        $productAbstractCategoryStorageEntity->setData($productAbstractCategoryStorageTransfer->toArray());
        $productAbstractCategoryStorageEntity->save();
    }
}
