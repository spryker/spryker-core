<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;
use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Orm\Zed\CmsSlotBlock\Persistence\SpyCmsSlotBlock;
use Propel\Runtime\Collection\Collection;

class CmsSlotBlockMapper implements CmsSlotBlockMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer $cmsSlotBlockTransfer
     * @param \Orm\Zed\CmsSlotBlock\Persistence\SpyCmsSlotBlock $cmsSlotBlockEntity
     *
     * @return \Orm\Zed\CmsSlotBlock\Persistence\SpyCmsSlotBlock
     */
    public function mapCmsSlotBlockTransferToEntity(
        CmsSlotBlockTransfer $cmsSlotBlockTransfer,
        SpyCmsSlotBlock $cmsSlotBlockEntity
    ): SpyCmsSlotBlock {
        $cmsSlotBlockEntity->fromArray($cmsSlotBlockTransfer->toArray());
        $cmsSlotBlockEntity->setFkCmsSlot($cmsSlotBlockTransfer->getIdSlot());
        $cmsSlotBlockEntity->setFkCmsBlock($cmsSlotBlockTransfer->getIdCmsBlock());
        $cmsSlotBlockEntity->setFkCmsSlotTemplate($cmsSlotBlockTransfer->getIdSlotTemplate());

        return $cmsSlotBlockEntity;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection $cmsSlotBlockEntities
     * @param \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer
     */
    public function mapCmsSlotBlockEntityCollectionToTransferCollection(
        Collection $cmsSlotBlockEntities,
        CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer
    ): CmsSlotBlockCollectionTransfer {
        foreach ($cmsSlotBlockEntities as $cmsSlotBlockEntity) {
            $cmsSlotBlockCollectionTransfer->addCmsSlotBlocks(
                $this->mapCmsSlotBlockEntityToTransfer($cmsSlotBlockEntity, new CmsSlotBlockTransfer())
            );
        }

        return $cmsSlotBlockCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\CmsSlotBlock\Persistence\SpyCmsSlotBlock $cmsSlotBlockEntity
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer $cmsSlotBlockTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockTransfer
     */
    protected function mapCmsSlotBlockEntityToTransfer(
        SpyCmsSlotBlock $cmsSlotBlockEntity,
        CmsSlotBlockTransfer $cmsSlotBlockTransfer
    ): CmsSlotBlockTransfer {
        $cmsSlotBlockTransfer->fromArray($cmsSlotBlockEntity->toArray(), true);
        $cmsSlotBlockTransfer->setIdSlotTemplate($cmsSlotBlockEntity->getFkCmsSlotTemplate())
            ->setIdSlot($cmsSlotBlockEntity->getFkCmsSlot())
            ->setIdCmsBlock($cmsSlotBlockEntity->getFkCmsBlock());

        return $cmsSlotBlockTransfer;
    }
}
