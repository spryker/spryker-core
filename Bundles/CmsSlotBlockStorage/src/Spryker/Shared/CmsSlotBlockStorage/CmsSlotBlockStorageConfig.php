<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CmsSlotBlockStorage;

use Spryker\Shared\Kernel\AbstractSharedConfig;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
class CmsSlotBlockStorageConfig extends AbstractSharedConfig
{
    /**
     * Specification:
     * - Queue name as used for processing CMS slot block messages.
     *
     * @api
     */
    public const CMS_SLOT_BLOCK_SYNC_STORAGE_QUEUE = 'sync.storage.cms';

    /**
     * Specification:
     * - Resource name, that is used for key generating.
     *
     * @api
     */
    public const CMS_SLOT_BLOCK_RESOURCE_NAME = 'cms_slot_block';
}
