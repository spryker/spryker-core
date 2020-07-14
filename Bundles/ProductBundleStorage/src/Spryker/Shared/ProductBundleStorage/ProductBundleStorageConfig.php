<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductBundleStorage;

class ProductBundleStorageConfig
{
    /**
     * Specification:
     * - Queue name as used for processing product_bundle messages.
     *
     * @api
     */
    public const PRODUCT_BUNDLE_SYNC_STORAGE_QUEUE = 'sync.storage.product_bundle';

    /**
     * Specification:
     * - Queue name as used for error product_bundle messages.
     *
     * @api
     */
    public const PRODUCT_BUNDLE_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.product_bundle.error';

    /**
     * Specification:
     * - Resource name, this will use for key generating.
     *
     * @api
     */
    public const PRODUCT_BUNDLE_RESOURCE_NAME = 'product_bundle';
}
