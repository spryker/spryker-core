<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductQuantityStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ProductQuantityStorageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Queue name as used for processing price messages
     *
     * @api
     */
    const PRODUCT_QUANTITY_SYNC_STORAGE_QUEUE = 'sync.storage.product';

    /**
     * Specification:
     * - Queue name as used for processing price messages
     *
     * @api
     */
    const PRODUCT_QUANTITY_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.product.error';

    /**
     * Specification:
     * - Key generation resource name of product quantity.
     *
     * @api
     */
    public const PRODUCT_QUANTITY_RESOURCE_NAME = 'product_quantity';
}
