<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CmsSlotStorage;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
class CmsSlotStorageConstants
{
    /**
     * Specification:
     * - Queue name as used for processing cms slot messages.
     *
     * @api
     */
    public const CMS_SLOT_SYNC_STORAGE_QUEUE = 'sync.storage.cms';

    /**
     * Specification:
     * - Resource name, this will use for key generating.
     *
     * @api
     */
    public const CMS_SLOT_RESOURCE_NAME = 'cms_slot';
}
