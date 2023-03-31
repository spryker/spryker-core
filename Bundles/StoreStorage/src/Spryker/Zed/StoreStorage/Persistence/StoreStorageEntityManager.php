<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreStorage\Persistence;

use Generated\Shared\Transfer\StoreStorageTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\StoreStorage\Persistence\StoreStoragePersistenceFactory getFactory()
 */
class StoreStorageEntityManager extends AbstractEntityManager implements StoreStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoreStorageTransfer $storeStorageTransfer
     *
     * @return void
     */
    public function updateStoreStorage(StoreStorageTransfer $storeStorageTransfer): void
    {
        $storeStorageEntity = $this->getFactory()
            ->createStoreStorageQuery()
            ->filterByFkStore($storeStorageTransfer->getIdStore())
            ->findOneOrCreate();

        $storeStorageEntity->setData($storeStorageTransfer->toArray());
        $storeStorageEntity->setStoreName($storeStorageTransfer->getNameOrFail());
        $storeStorageEntity->save();
    }

    /**
     * @param array<string> $storeNames
     *
     * @return void
     */
    public function updateStoreList(array $storeNames): void
    {
        $this->getFactory()
            ->createStoreListStorageQuery()
            ->findOneOrCreate()
            ->setData(['stores' => $storeNames])
            ->save();
    }
}
