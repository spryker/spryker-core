<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleStorage\Persistence;

use Generated\Shared\Transfer\ProductBundleStorageTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductBundleStorage\Persistence\ProductBundleStoragePersistenceFactory getFactory()
 */
class ProductBundleStorageEntityManager extends AbstractEntityManager implements ProductBundleStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductBundleStorageTransfer $productBundleStorageTransfer
     *
     * @return void
     */
    public function saveProductBundleStorage(ProductBundleStorageTransfer $productBundleStorageTransfer): void
    {
        $productBundleStorageEntity = $this->getFactory()
            ->getProductBundleStoragePropelQuery()
            ->filterByFkProduct($productBundleStorageTransfer->getIdProductConcreteBundle())
            ->findOneOrCreate();

        $productBundleStorageEntity
            ->setData($productBundleStorageTransfer->toArray())
            ->save();
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function deleteProductBundleStorageEntities(array $productConcreteIds): void
    {
        $this->getFactory()
            ->getProductBundleStoragePropelQuery()
            ->filterByFkProduct_In($productConcreteIds)
            ->delete();
    }
}
