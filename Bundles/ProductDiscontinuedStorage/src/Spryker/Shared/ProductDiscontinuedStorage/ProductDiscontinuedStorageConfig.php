<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductDiscontinuedStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ProductDiscontinuedStorageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Key generation resource name of product discontinued.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_DISCONTINUED_RESOURCE_NAME = 'product_discontinued';

    /**
     * Specification:
     * - Queue name as used for processing product discontinued messages.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_DISCONTINUED_SYNC_STORAGE_QUEUE = 'sync.storage.product';

    /**
     * Specification:
     * - Queue name as used for processing product discontinued messages.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_DISCONTINUED_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.product.error';

    /**
     * Specification:
     * - This event is used for discontinued products publishing.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_DISCONTINUED_PUBLISH = 'ProductDiscontinued.product_discontinued.publish';
}
