<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlot\Persistence\Mapper;

use Generated\Shared\Transfer\CmsSlotTransfer;
use Orm\Zed\CmsSlot\Persistence\SpyCmsSlot;

interface CmsSlotMapperInterface
{
    /**
     * @param \Orm\Zed\CmsSlot\Persistence\SpyCmsSlot $cmsSlot
     *
     * @return \Generated\Shared\Transfer\CmsSlotTransfer
     */
    public function mapCmsSlotEntityToTransfer(SpyCmsSlot $cmsSlot): CmsSlotTransfer;

    /**
     * @param \Orm\Zed\CmsSlot\Persistence\SpyCmsSlot $cmsSlot
     * @param \Generated\Shared\Transfer\CmsSlotTransfer $cmsSlotTransfer
     *
     * @return \Orm\Zed\CmsSlot\Persistence\SpyCmsSlot
     */
    public function mapCmsSlotTransferToEntity(SpyCmsSlot $cmsSlot, CmsSlotTransfer $cmsSlotTransfer): SpyCmsSlot;

    /**
     * @param \Generated\Shared\Transfer\SpyOfferEntityTransfer[] $cmsSlotEntities
     *
     * @return \Generated\Shared\Transfer\CmsSlotTransfer[]
     */
    public function mapCmsSlotEntityCollectionToTransferCollection(array $cmsSlotEntities): array;
}
