<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedStorage\Persistence;

use Orm\Zed\ProductDiscontinuedStorage\Persistence\SpyProductDiscontinuedStorage;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStoragePersistenceFactory getFactory()
 */
class ProductDiscontinuedStorageEntityManager extends AbstractEntityManager implements ProductDiscontinuedStorageEntityManagerInterface
{
    /**
     * @param \Orm\Zed\ProductDiscontinuedStorage\Persistence\SpyProductDiscontinuedStorage $productDiscontinuedStorageEntity
     *
     * @return void
     */
    public function saveProductDiscontinuedStorageEntity(SpyProductDiscontinuedStorage $productDiscontinuedStorageEntity): void
    {
        $productDiscontinuedStorageEntity->save();
    }

    /**
     * @param \Orm\Zed\ProductDiscontinuedStorage\Persistence\SpyProductDiscontinuedStorage $productDiscontinuedStorageEntity
     *
     * @return void
     */
    public function deleteProductDiscontinuedStorageEntity(SpyProductDiscontinuedStorage $productDiscontinuedStorageEntity): void
    {
        $this->getFactory()
            ->createProductDiscontinuedStoragePropelQuery()
            ->findOneByIdProductDiscontinuedStorage($productDiscontinuedStorageEntity->getIdProductDiscontinuedStorage())
            ->delete();
    }
}
