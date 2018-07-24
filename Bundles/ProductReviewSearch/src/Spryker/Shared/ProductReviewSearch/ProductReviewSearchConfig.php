<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductReviewSearch;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ProductReviewSearchConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    const PRODUCT_REVIEW_RESOURCE_NAME = 'product_review';

    /**
     * //TODO add specification
     * @api
     */
    const PLUGIN_PRODUCT_PAGE_RATING_DATA = 'PLUGIN_PRODUCT_PAGE_RATING_DATA';

    /**
     * Specification:
     * - Queue name as used for processing Product messages
     *
     * @api
     */
    const PRODUCT_REVIEW_SYNC_SEARCH_QUEUE = 'sync.search.product';

    /**
     * Specification:
     * - Queue name as used for processing Product messages
     *
     * @api
     */
    const PRODUCT_REVIEW_SYNC_SEARCH_ERROR_QUEUE = 'sync.search.product.error';
}
