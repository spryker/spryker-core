<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Persistence;

use Generated\Shared\Transfer\CmsSlotBlockTransfer;

interface CmsSlotBlockEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer $cmsSlotBlockTransfer
     *
     * @return void
     */
    public function createCmsSlotBlock(CmsSlotBlockTransfer $cmsSlotBlockTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer $cmsSlotBlockTransfer
     *
     * @return void
     */
    public function updateCmsSlotBlock(CmsSlotBlockTransfer $cmsSlotBlockTransfer): void;

    /**
     * @param int $idCmsSlot
     * @param int[] $cmsBlockIds
     *
     * @return void
     */
    public function deleteCmsSlotBlocks(int $idCmsSlot, array $cmsBlockIds): void;
}
