<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductStorage;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
class ProductStorageConstants
{
    /**
     * Specification:
     * - Queue name as used for processing Product messages
     *
     * @api
     */
    const PRODUCT_SYNC_STORAGE_QUEUE = 'sync.storage.product';

    /**
     * Specification:
     * - Queue name as used for processing Product messages
     *
     * @api
     */
    const PRODUCT_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.product.error';

    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    const PRODUCT_ABSTRACT_RESOURCE_NAME = 'product_abstract';

    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    const PRODUCT_CONCRETE_RESOURCE_NAME = 'product_concrete';
}
