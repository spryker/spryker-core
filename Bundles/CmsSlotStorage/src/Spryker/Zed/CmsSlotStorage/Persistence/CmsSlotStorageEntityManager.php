<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotStorage\Persistence;

use Generated\Shared\Transfer\CmsSlotTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\CmsSlotStorage\Persistence\CmsSlotStoragePersistenceFactory getFactory()
 */
class CmsSlotStorageEntityManager extends AbstractEntityManager implements CmsSlotStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsSlotTransfer $cmsSlotTransfer
     *
     * @return void
     */
    public function saveCmsSlotStorage(CmsSlotTransfer $cmsSlotTransfer): void
    {
        $cmsSlotTransfer->requireKey();

        $cmsSlotStorageEntity = $this->getFactory()
            ->getCmsSlotStorageQuery()
            ->filterByCmsSlotKey($cmsSlotTransfer->getKey())
            ->findOneOrCreate();

        $cmsSlotStorageEntity = $this->getFactory()
            ->createCmsSlotStorageMapper()
            ->mapCmsSlotTransferToStorageEntity($cmsSlotTransfer, $cmsSlotStorageEntity);

        $cmsSlotStorageEntity->save();
    }

    /**
     * @param int $idCmsSlotStorage
     *
     * @return void
     */
    public function deleteCmsSlotStorageById(int $idCmsSlotStorage): void
    {
        $cmsSlotStorageEntity = $this->getFactory()
            ->getCmsSlotStorageQuery()
            ->filterByIdCmsSlotStorage($idCmsSlotStorage)
            ->findOne();

        if (!$cmsSlotStorageEntity) {
            return;
        }

        $cmsSlotStorageEntity->delete();
    }
}
