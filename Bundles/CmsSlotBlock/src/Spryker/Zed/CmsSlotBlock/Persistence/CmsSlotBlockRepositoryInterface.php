<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Persistence;

use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;

interface CmsSlotBlockRepositoryInterface
{
    /**
     * @param int $idCmsSlotTemplate
     * @param int $idCmsSlot
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer
     */
    public function getCmsSlotBlocks(int $idCmsSlotTemplate, int $idCmsSlot): CmsSlotBlockCollectionTransfer;

    /**
     * @return \Generated\Shared\Transfer\CmsBlockTransfer[]
     */
    public function getCmsBlocksWithSlotRelations(): array;
}
