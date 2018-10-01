<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductSetPageSearch;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
class ProductSetPageSearchConstants
{
    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    public const PRODUCT_SET_RESOURCE_NAME = 'product_set';

    /**
     * Specification:
     * - Queue name as used for processing Product messages
     *
     * @api
     */
    public const PRODUCT_SET_SYNC_SEARCH_QUEUE = 'sync.search.product';

    /**
     * Specification:
     * - Queue name as used for processing Product messages
     *
     * @api
     */
    public const PRODUCT_SET_SYNC_SEARCH_ERROR_QUEUE = 'sync.search.product.error';
}
