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
    protected const MAX_CMS_BLOCKS_IN_BLOCK_SELECTOR = 500;

    /**
     * @api
     *
     * @return int
     */
    public function getMaxNumberBlocksAssignedToSlot(): int
    {
        return static::MAX_NUMBER_BLOCKS_ASSIGNED_TO_SLOT;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getMaxCmsBlocksInBlockSelector(): int
    {
        return static::MAX_CMS_BLOCKS_IN_BLOCK_SELECTOR;
    }
}
