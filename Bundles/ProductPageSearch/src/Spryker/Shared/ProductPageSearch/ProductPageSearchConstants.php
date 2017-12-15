<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductPageSearch;

class ProductPageSearchConstants
{

    /**
     * Specification:
     * - Queue name as used for processing Product messages
     *
     * @api
     */
    const PRODUCT_SYNC_SEARCH_QUEUE = 'sync.search.product';

    /**
     * Specification:
     * - Queue name as used for processing Product messages
     *
     * @api
     */
    const PRODUCT_SYNC_SEARCH_ERROR_QUEUE = 'sync.search.product.error';

    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    const PRODUCT_ABSTRACT_RESOURCE_NAME = 'product_abstract';

}
