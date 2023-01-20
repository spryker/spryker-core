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
     *
     * @var string
     */
    public const CATEGORY_IMAGE_SYNC_STORAGE_QUEUE = 'sync.storage.category';

    /**
     * Specification:
     * - Queue name as used for processing category image messages
     *
     * @api
     *
     * @var string
     */
    public const CATEGORY_IMAGE_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.category.error';

    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     *
     * @var string
     */
    public const CATEGORY_IMAGE_RESOURCE_NAME = 'category_image';

    /**
     * Specification:
     * - This event will be used for category image publishing.
     *
     * @api
     *
     * @uses {@link \Spryker\Zed\CategoryImage\Dependency\CategoryImageEvents::CATEGORY_IMAGE_CATEGORY_PUBLISH}
     *
     * @var string
     */
    public const CATEGORY_IMAGE_CATEGORY_PUBLISH = 'CategoryImage.category_image.publish';

    /**
     * Specification:
     * - Default image set name.
     *
     * @api
     *
     * @var string
     */
    public const DEFAULT_IMAGE_SET_NAME = 'default';
}
