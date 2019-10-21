<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CmsSlotBlockGuiConfig extends AbstractBundleConfig
{
    protected const MAX_NUMBER_BLOCKS_ASSIGNED_TO_SLOT = 500;

    /**
     * @return int
     */
    public function getMaxNumberBlocksAssignedToSlot(): int
    {
        return static::MAX_NUMBER_BLOCKS_ASSIGNED_TO_SLOT;
    }
}
