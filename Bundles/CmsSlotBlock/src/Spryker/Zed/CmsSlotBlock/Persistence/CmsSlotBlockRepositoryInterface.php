<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Persistence;

use Generated\Shared\Transfer\CmsBlockCollectionTransfer;
use Generated\Shared\Transfer\CmsBlockCriteriaTransfer;
use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;
use Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer;
use Generated\Shared\Transfer\FilterTransfer;

interface CmsSlotBlockRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer
     */
    public function getCmsSlotBlocks(
        CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer
    ): CmsSlotBlockCollectionTransfer;

    /**
     * @deprecated Use {@link getPaginatedCmsBlocksWithSlotRelations()} instead.
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer[]
     */
    public function getCmsBlocksWithSlotRelations(FilterTransfer $filterTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\CmsBlockCriteriaTransfer $cmsBlockCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockCollectionTransfer
     */
    public function getPaginatedCmsBlocksWithSlotRelations(CmsBlockCriteriaTransfer $cmsBlockCriteriaTransfer): CmsBlockCollectionTransfer;
}
