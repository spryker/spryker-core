<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Persistence;

use Generated\Shared\Transfer\ProductAbstractLabelStorageTransfer;
use Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer;
use Orm\Zed\ProductLabelStorage\Persistence\SpyProductAbstractLabelStorage;
use Orm\Zed\ProductLabelStorage\Persistence\SpyProductLabelDictionaryStorage;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStoragePersistenceFactory getFactory()
 */
class ProductLabelStorageEntityManager extends AbstractEntityManager implements ProductLabelStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAbstractLabelStorageTransfer $productAbstractLabelStorageTransfer
     *
     * @return void
     */
    public function saveProductAbstractLabelStorage(ProductAbstractLabelStorageTransfer $productAbstractLabelStorageTransfer): void
    {
        $productAbstractLabelStorageEntity = $this->getFactory()
            ->createSpyProductAbstractLabelStorageQuery()
            ->filterByFkProductAbstract($productAbstractLabelStorageTransfer->getIdProductAbstract())
            ->findOneOrCreate();

        $productAbstractLabelStorageEntity->setData($productAbstractLabelStorageTransfer->getProductLabelIds());
        $productAbstractLabelStorageEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer $productLabelDictionaryStorageTransfer
     *
     * @return void
     */
    public function saveProductLabelDictionaryStorage(ProductLabelDictionaryStorageTransfer $productLabelDictionaryStorageTransfer): void
    {
        $productLabelDictionaryStorageEntity = $this->getFactory()
            ->createSpyProductLabelDictionaryStorageQuery()
            ->filterByLocale($productLabelDictionaryStorageTransfer->getLocale())
            ->findOneOrCreate();

        $productLabelDictionaryStorageEntity->setData($productLabelDictionaryStorageTransfer->getItems()->getArrayCopy());
        $productLabelDictionaryStorageEntity->save();
    }

    /**
     * @return void
     */
    public function deleteAllProductLabelDictionaryStorageEntities(): void
    {
        $this->getFactory()
            ->createSpyProductLabelDictionaryStorageQuery()
            ->find()
            ->delete();
    }

    /**
     * @param int $productAbstractId
     *
     * @return void
     */
    public function deleteProductAbstractLabelStorageByProductAbstractId(int $productAbstractId): void
    {
        $this->getFactory()
            ->createSpyProductAbstractLabelStorageQuery()
            ->filterByFkProductAbstract($productAbstractId)
            ->find()
            ->delete();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function deleteProductAbstractLabelStorageEntitiesByProductAbstractIds(array $productAbstractIds): void
    {
        $this->getFactory()
            ->createSpyProductAbstractLabelStorageQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->find()
            ->delete();
    }
}
