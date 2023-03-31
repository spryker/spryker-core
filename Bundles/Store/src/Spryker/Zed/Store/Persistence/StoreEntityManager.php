<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Persistence;

use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Store\Persistence\SpyStore;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Store\Persistence\StorePersistenceFactory getFactory()
 */
class StoreEntityManager extends AbstractEntityManager implements StoreEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function createStore(StoreTransfer $storeTransfer): StoreTransfer
    {
        $storeEntity = (new SpyStore())
            ->setName($storeTransfer->getNameOrFail());

        $storeEntity->save();

        $storeTransfer->setIdStore($storeEntity->getIdStore());

        return $storeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function updateStore(StoreTransfer $storeTransfer): StoreTransfer
    {
        $this->getFactory()
            ->createStoreQuery()
            ->requirePk($storeTransfer->getIdStoreOrFail())
            ->setName($storeTransfer->getNameOrFail())
            ->save();

        return $storeTransfer;
    }
}
