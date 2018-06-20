<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage\Persistence;

use Generated\Shared\Transfer\SpyProductAbstractProductListStorageEntityTransfer;
use Generated\Shared\Transfer\SpyProductConcreteProductListStorageEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductListStorage\Persistence\ProductListStoragePersistenceFactory getFactory()
 */
class ProductListStorageEntityManager extends AbstractEntityManager implements ProductListStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyProductAbstractProductListStorageEntityTransfer $productAbstractProductListStorageEntityTransfer
     *
     * @return void
     */
    public function saveProductAbstractProductListStorage(SpyProductAbstractProductListStorageEntityTransfer $productAbstractProductListStorageEntityTransfer): void
    {
        $productAbstractProductListStorageEntityTransfer->requireFkProductAbstract();

        $this->save($productAbstractProductListStorageEntityTransfer);
    }

    /**
     * @param int $idProductAbstractProductListStorage
     *
     * @return void
     */
    public function deleteProductAbstractProductListStorage(int $idProductAbstractProductListStorage): void
    {
        $this->getFactory()
            ->createProductAbstractProductListStorageQuery()
            ->filterByIdProductAbstractProductListStorage($idProductAbstractProductListStorage)
            ->findOne()
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductConcreteProductListStorageEntityTransfer $productConcreteProductListStorageEntityTransfer
     *
     * @return void
     */
    public function saveProductConcreteProductListStorage(SpyProductConcreteProductListStorageEntityTransfer $productConcreteProductListStorageEntityTransfer): void
    {
        $productConcreteProductListStorageEntityTransfer->requireFkProduct();

        $this->save($productConcreteProductListStorageEntityTransfer);
    }

    /**
     * @param int $idProductConcreteProductListStorage
     *
     * @return void
     */
    public function deleteProductConcreteProductListStorage(int $idProductConcreteProductListStorage): void
    {
        $this->getFactory()
            ->createProductConcreteProductListStorageQuery()
            ->filterByIdProductConcreteProductListStorage($idProductConcreteProductListStorage)
            ->findOne()
            ->delete();
    }
}
