<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotStorage\Persistence;

use Generated\Shared\Transfer\CmsSlotStorageTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\CmsSlotStorage\Persistence\CmsSlotStoragePersistenceFactory getFactory()
 */
class CmsSlotStorageEntityManager extends AbstractEntityManager implements CmsSlotStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsSlotStorageTransfer $cmsSlotStorageTransfer
     *
     * @return void
     */
    public function saveCmsSlotStorage(CmsSlotStorageTransfer $cmsSlotStorageTransfer): void
    {
        $cmsSlotStorageTransfer->requireKey();

        $cmsSlotStorageEntity = $this->getFactory()
            ->getCmsSlotStorageQuery()
            ->filterByCmsSlotKey($cmsSlotStorageTransfer->getKey())
            ->findOneOrCreate();

        $cmsSlotStorageEntity = $this->getFactory()
            ->createCmsSlotStorageMapper()
            ->mapCmsSlotStorageTransferToEntity($cmsSlotStorageTransfer, $cmsSlotStorageEntity);

        $cmsSlotStorageEntity->save();
    }

    /**
     * @param int $idCmsSlotStorage
     *
     * @return void
     */
    public function deleteCmsSlotStorageById(int $idCmsSlotStorage): void
    {
        $this->getFactory()
            ->getCmsSlotStorageQuery()
            ->filterByIdCmsSlotStorage($idCmsSlotStorage)
            ->delete();
    }
}
