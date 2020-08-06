<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockStorage\Persistence;

use Generated\Shared\Transfer\CmsSlotBlockStorageTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\CmsSlotBlockStorage\Persistence\CmsSlotBlockStoragePersistenceFactory getFactory()
 */
class CmsSlotBlockStorageEntityManager extends AbstractEntityManager implements CmsSlotBlockStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockStorageTransfer $cmsSlotBlockStorageTransfer
     *
     * @return void
     */
    public function saveCmsSlotBlockStorage(CmsSlotBlockStorageTransfer $cmsSlotBlockStorageTransfer): void
    {
        $cmsSlotBlockStorageEntity = $this->getFactory()
            ->createCmsSlotBlockStorageQuery()
            ->filterByFkCmsSlot($cmsSlotBlockStorageTransfer->getIdCmsSlot())
            ->filterByFkCmsSlotTemplate($cmsSlotBlockStorageTransfer->getIdCmsSlotTemplate())
            ->findOneOrCreate();

        $cmsSlotBlockStorageEntity = $this->getFactory()
            ->createCmsSlotBlockStorageMapper()
            ->mapCmsSlotBlockStorageTransferToCmsSlotBlockStorageEntity(
                $cmsSlotBlockStorageTransfer,
                $cmsSlotBlockStorageEntity
            );

        $cmsSlotBlockStorageEntity->setIsSendingToQueue($this->getFactory()->getConfig()->isSendingToQueue());

        $cmsSlotBlockStorageEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockStorageTransfer $cmsSlotBlockStorageTransfer
     *
     * @return void
     */
    public function deleteCmsSlotBlockStorage(
        CmsSlotBlockStorageTransfer $cmsSlotBlockStorageTransfer
    ): void {
        $cmsSlotBlockStorageEntity = $this->getFactory()
            ->createCmsSlotBlockStorageQuery()
            ->filterByFkCmsSlot($cmsSlotBlockStorageTransfer->getIdCmsSlot())
            ->filterByFkCmsSlotTemplate($cmsSlotBlockStorageTransfer->getIdCmsSlotTemplate())
            ->findOne();

        if (!$cmsSlotBlockStorageEntity) {
            return;
        }

        $cmsSlotBlockStorageEntity->delete();
    }
}
