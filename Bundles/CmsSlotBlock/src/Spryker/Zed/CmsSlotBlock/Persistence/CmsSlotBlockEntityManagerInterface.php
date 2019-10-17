<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Persistence;

interface CmsSlotBlockEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer[] $cmsSlotBlockTransfers
     *
     * @return void
     */
    public function createCmsSlotBlocks(array $cmsSlotBlockTransfers): void;

    /**
     * @param int $idSlotTemplate
     * @param int[] $cmsSlotIds
     *
     * @return void
     */
    public function deleteCmsSlotBlocks(int $idSlotTemplate, array $cmsSlotIds): void;
}
