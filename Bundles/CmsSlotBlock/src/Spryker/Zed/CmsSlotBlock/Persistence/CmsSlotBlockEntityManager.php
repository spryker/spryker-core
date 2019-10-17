<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Persistence;

use Orm\Zed\CmsSlotBlock\Persistence\SpyCmsSlotBlock;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockPersistenceFactory getFactory()
 */
class CmsSlotBlockEntityManager extends AbstractEntityManager implements CmsSlotBlockEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer[] $cmsSlotBlockTransfers
     *
     * @return void
     */
    public function createCmsSlotBlocks(array $cmsSlotBlockTransfers): void
    {
        $cmsSlotBlockEntityCollection = new ObjectCollection();
        $cmsSlotBlockEntityCollection->setModel(SpyCmsSlotBlock::class);

        $cmsSlotBlockMapper = $this->getFactory()->createCmsSlotBlockMapper();

        foreach ($cmsSlotBlockTransfers as $cmsSlotBlockTransfer) {
            $cmsSlotBlockTransfer->requireIdSlot()
                ->requireIdCmsBlock()
                ->requireIdSlotTemplate()
                ->requirePosition();

            $cmsSlotBlockEntity = $cmsSlotBlockMapper->mapCmsSlotBlockTransferToEntity(
                $cmsSlotBlockTransfer,
                new SpyCmsSlotBlock()
            );

            $cmsSlotBlockEntityCollection->append($cmsSlotBlockEntity);
        }

        $cmsSlotBlockEntityCollection->save();
    }

    /**
     * @param int $idSlotTemplate
     * @param int[] $cmsSlotIds
     *
     * @return void
     */
    public function deleteCmsSlotBlocks(int $idSlotTemplate, array $cmsSlotIds): void
    {
        $this->getFactory()
            ->getCmsSlotBlockQuery()
            ->filterByFkCmsSlotTemplate($idSlotTemplate)
            ->filterByFkCmsSlot_In($cmsSlotIds)
            ->delete();
    }
}
