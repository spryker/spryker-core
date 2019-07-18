<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CmsBlockProductStorage;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
class CmsBlockProductStorageConstants
{
    /**
     * Specification:
     * - Queue name as used for processing category messages
     *
     * @api
     */
    public const CMS_BLOCK_PRODUCT_SYNC_STORAGE_QUEUE = 'sync.storage.cms';

    /**
     * Specification:
     * - Queue name as used for error category messages
     *
     * @api
     */
    public const CMS_BLOCK_PRODUCT_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.cms.error';

    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    public const CMS_BLOCK_PRODUCT_RESOURCE_NAME = 'cms_block_product';
}
