<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductAlternativeStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ProductAlternativeStorageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Key generation resource name of product alternative.
     *
     * @api
     * @var string
     */
    public const PRODUCT_ALTERNATIVE_RESOURCE_NAME = 'product_alternative';

    /**
     * Specification:
     * - Key generation resource name of product replacement.
     *
     * @api
     * @var string
     */
    public const PRODUCT_REPLACEMENT_RESOURCE_NAME = 'product_replacement_for';

    /**
     * Specification:
     * - Queue name as used for processing product alternative messages.
     *
     * @api
     * @var string
     */
    public const PRODUCT_ALTERNATIVE_SYNC_STORAGE_QUEUE = 'sync.storage.product';

    /**
     * Specification:
     * - Queue name as used for processing product alternative messages.
     *
     * @api
     * @var string
     */
    public const PRODUCT_ALTERNATIVE_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.product.error';
}
