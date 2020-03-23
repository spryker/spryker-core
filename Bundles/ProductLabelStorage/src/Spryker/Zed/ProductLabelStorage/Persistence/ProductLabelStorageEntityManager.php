<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Persistence;

use Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer;
use Orm\Zed\ProductLabelStorage\Persistence\SpyProductLabelDictionaryStorage;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStoragePersistenceFactory getFactory()
 */
class ProductLabelStorageEntityManager extends AbstractEntityManager implements ProductLabelStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer $productLabelDictionaryStorageTransfer
     *
     * @return void
     */
    public function createProductLabelDictionaryStorage(ProductLabelDictionaryStorageTransfer $productLabelDictionaryStorageTransfer): void
    {
        $productLabelDictionaryStorageEntity = $this->getFactory()->createProductLabelDictionaryStorageMapper()
            ->mapProductLabelDictionaryStorageTransferToProductLabelDictionaryStorageEntity(
                $productLabelDictionaryStorageTransfer,
                new SpyProductLabelDictionaryStorage()
            );

        $productLabelDictionaryStorageEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer $productLabelDictionaryStorageTransfer
     *
     * @return void
     */
    public function updateProductLabelDictionaryStorage(ProductLabelDictionaryStorageTransfer $productLabelDictionaryStorageTransfer): void
    {
        $productLabelDictionaryStorageEntity = $this->getFactory()
            ->createSpyProductLabelDictionaryStorageQuery()
            ->filterByIdProductLabelDictionaryStorage($productLabelDictionaryStorageTransfer->getIdProductLabelDictionaryStorage())
            ->findOne();

        $productLabelDictionaryStorageEntity = $this->getFactory()->createProductLabelDictionaryStorageMapper()
            ->mapProductLabelDictionaryStorageTransferToProductLabelDictionaryStorageEntity(
                $productLabelDictionaryStorageTransfer,
                $productLabelDictionaryStorageEntity ?? new SpyProductLabelDictionaryStorage()
            );

        $productLabelDictionaryStorageEntity->save();
    }

    /**
     * @param int $idProductLabelDictionary
     *
     * @return void
     */
    public function deleteProductLabelDictionaryStorageById(int $idProductLabelDictionary): void
    {
        $this->getFactory()
            ->createSpyProductLabelDictionaryStorageQuery()
            ->filterByIdProductLabelDictionaryStorage($idProductLabelDictionary)
            ->delete();
    }
}
