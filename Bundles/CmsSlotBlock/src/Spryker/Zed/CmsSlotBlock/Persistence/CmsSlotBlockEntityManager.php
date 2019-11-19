<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Persistence;

use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;
use Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer;
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
     * @return \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer
     */
    public function createCmsSlotBlocks(array $cmsSlotBlockTransfers): CmsSlotBlockCollectionTransfer
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

        return $cmsSlotBlockMapper->mapCmsSlotBlockEntityCollectionToTransferCollection(
            $cmsSlotBlockEntityCollection,
            new CmsSlotBlockCollectionTransfer()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer
     *
     * @return void
     */
    public function deleteCmsSlotBlocksByCriteria(CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer): void
    {
        $cmsSlotBlockCriteriaTransfer->requireIdCmsSlotTemplate()
            ->requireIdCmsSlot();

        $this->getFactory()
            ->getCmsSlotBlockQuery()
            ->filterByFkCmsSlotTemplate($cmsSlotBlockCriteriaTransfer->getIdCmsSlotTemplate())
            ->filterByFkCmsSlot($cmsSlotBlockCriteriaTransfer->getIdCmsSlot())
            ->delete();
    }
}
