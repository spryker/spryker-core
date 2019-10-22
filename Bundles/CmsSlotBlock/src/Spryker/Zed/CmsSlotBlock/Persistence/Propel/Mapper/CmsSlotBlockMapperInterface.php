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

interface CmsSlotBlockMapperInterface
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
    ): SpyCmsSlotBlock;

    /**
     * @param \Propel\Runtime\Collection\Collection $cmsSlotBlockEntities
     * @param \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer
     */
    public function mapCmsSlotBlockEntityCollectionToTransferCollection(
        Collection $cmsSlotBlockEntities,
        CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer
    ): CmsSlotBlockCollectionTransfer;
}
