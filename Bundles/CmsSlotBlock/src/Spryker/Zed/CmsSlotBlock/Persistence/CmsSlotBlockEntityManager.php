<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Persistence;

use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Orm\Zed\CmsSlotBlock\Persistence\SpyCmsSlotBlock;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockPersistenceFactory getFactory()
 */
class CmsSlotBlockEntityManager extends AbstractEntityManager implements CmsSlotBlockEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer $cmsSlotBlockTransfer
     *
     * @return void
     */
    public function createCmsSlotBlock(CmsSlotBlockTransfer $cmsSlotBlockTransfer): void
    {
        $cmsSlotBlockTransfer->requireIdCmsBlock()
            ->requireIdSlot()
            ->requirePosition();

        $cmsSlotBlockEntity = $this->getFactory()
            ->createCmsSlotBlockMapper()
            ->mapCmsSlotBlockTransferToEntity($cmsSlotBlockTransfer, new SpyCmsSlotBlock());

        $cmsSlotBlockEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer $cmsSlotBlockTransfer
     *
     * @return void
     */
    public function updateCmsSlotBlock(CmsSlotBlockTransfer $cmsSlotBlockTransfer): void
    {
        $cmsSlotBlockTransfer->requireIdCmsBlock()
            ->requireIdSlot()
            ->requirePosition();

        $cmsSlotBlockEntity = $this->getFactory()
            ->getCmsSLotBlockQuery()
            ->filterByFkCmsBlock($cmsSlotBlockTransfer->getIdCmsBlock())
            ->filterByFkCmsSlot($cmsSlotBlockTransfer->getIdSlot())
            ->findOne();

        if (!$cmsSlotBlockEntity) {
            return;
        }

        $cmsSlotBlockEntity = $this->getFactory()
            ->createCmsSlotBlockMapper()
            ->mapCmsSlotBlockTransferToEntity($cmsSlotBlockTransfer, $cmsSlotBlockEntity);

        $cmsSlotBlockEntity->save();
    }

    /**
     * @param int $idCmsSlot
     * @param int[] $cmsBlockIds
     *
     * @return void
     */
    public function deleteCmsSlotBlocks(int $idCmsSlot, array $cmsBlockIds): void
    {
        $this->getFactory()
            ->getCmsSLotBlockQuery()
            ->filterByFkCmsSlot($idCmsSlot)
            ->filterByFkCmsBlock_In($cmsBlockIds)
            ->delete();
    }
}
