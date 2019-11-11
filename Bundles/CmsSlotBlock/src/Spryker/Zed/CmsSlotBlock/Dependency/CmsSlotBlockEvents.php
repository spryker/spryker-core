<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Dependency;

interface CmsSlotBlockEvents
{
    /**
     * Specification
     * - This events will be used for CmsSlotBlock publishing.
     *
     * @api
     */
    public const CMS_SLOT_BLOCK_PUBLISH = 'CmsSlotBlock.slot_block.publish';
}
