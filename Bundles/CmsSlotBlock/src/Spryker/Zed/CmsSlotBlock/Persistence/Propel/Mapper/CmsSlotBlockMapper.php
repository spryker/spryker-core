<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Orm\Zed\CmsSlotBlock\Persistence\SpyCmsSlotBlock;
use Propel\Runtime\Collection\Collection;

class CmsSlotBlockMapper implements CmsSlotBlockMapperInterface
{
    /**
     * @param \Propel\Runtime\Collection\Collection $cmsSlotBlockEntities
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockTransfer[]
     */
    public function mapCmsSlotBlockEntitiesToTransfers(Collection $cmsSlotBlockEntities): array
    {
        $cmsSlotBlockTransfers = [];
        foreach ($cmsSlotBlockEntities as $cmsSlotBlockEntity) {
            $cmsSlotBlockTransfer = (new CmsSlotBlockTransfer())
                ->fromArray($cmsSlotBlockEntity->toArray(), true)
                ->setIdSlot($cmsSlotBlockEntity->getFkCmsSlot())
                ->setIdCmsBlock($cmsSlotBlockEntity->getFkCmsBlock());

            $cmsSlotBlockTransfers[] = $cmsSlotBlockTransfer;
        }

        return $cmsSlotBlockTransfers;
    }

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
        $cmsSlotBlockEntity->fromArray($cmsSlotBlockTransfer->modifiedToArray());
        $cmsSlotBlockEntity->setFkCmsBlock($cmsSlotBlockTransfer->getIdCmsBlock());
        $cmsSlotBlockEntity->setFkCmsSlot($cmsSlotBlockTransfer->getIdSlot());

        return $cmsSlotBlockEntity;
    }
}
