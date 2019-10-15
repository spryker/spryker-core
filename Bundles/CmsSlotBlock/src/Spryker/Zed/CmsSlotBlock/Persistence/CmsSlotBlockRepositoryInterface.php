<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Persistence;

interface CmsSlotBlockRepositoryInterface
{
    /**
     * @param int[] $slotIds
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockTransfer[]
     */
    public function getCmsSlotBlocksBySlotIds(array $slotIds): array;
}
