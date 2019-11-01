<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CmsSlotStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class CmsSlotStorageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Queue name as used for processing CMS slot messages.
     *
     * @api
     */
    public const CMS_SLOT_SYNC_STORAGE_QUEUE = 'sync.storage.cms';

    /**
     * Specification:
     * - Resource name, it will be used for key generating.
     *
     * @api
     */
    public const CMS_SLOT_RESOURCE_NAME = 'cms_slot';
}
