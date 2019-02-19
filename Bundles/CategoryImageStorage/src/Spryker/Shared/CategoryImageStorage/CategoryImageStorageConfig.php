<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CategoryImageStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class CategoryImageStorageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Queue name as used for processing category image messages
     *
     * @api
     */
    public const CATEGORY_IMAGE_SYNC_STORAGE_QUEUE = 'sync.storage.category';

    /**
     * Specification:
     * - Queue name as used for processing category image messages
     *
     * @api
     */
    public const CATEGORY_IMAGE_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.category.error';

    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    public const CATEGORY_IMAGE_RESOURCE_NAME = 'category_image';

    /**
     * Specification:
     * - Default image set name.
     *
     * @api
     */
    public const DEFAULT_IMAGE_SET_NAME = 'default';
}
