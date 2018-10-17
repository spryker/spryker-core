<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductImageStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;
use Spryker\Shared\ProductImage\ProductImageConfig;

class ProductImageStorageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Queue name as used for processing price messages
     *
     * @api
     */
    public const PRODUCT_ABSTRACT_IMAGE_SYNC_STORAGE_QUEUE = 'sync.storage.product';

    /**
     * Specification:
     * - Queue name as used for processing price messages
     *
     * @api
     */
    public const PRODUCT_ABSTRACT_IMAGE_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.product.error';

    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    public const PRODUCT_ABSTRACT_IMAGE_RESOURCE_NAME = 'product_abstract_image';

    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    public const PRODUCT_CONCRETE_IMAGE_RESOURCE_NAME = 'product_concrete_image';

    /**
     * Specification:
     * - Default image set name.
     *
     * @api
     */
    public const DEFAULT_IMAGE_SET_NAME = ProductImageConfig::DEFAULT_IMAGE_SET_NAME;
}
