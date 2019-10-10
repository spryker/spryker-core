<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ProductReviewsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_PRODUCT_REVIEWS = 'product-reviews';

    public const CONTROLLER_PRODUCT_REVIEWS = 'product-reviews-resource';

    public const RESPONSE_CODE_CANT_FIND_PRODUCT_REVIEW = '3101';
    public const RESPONSE_DETAIL_CANT_FIND_PRODUCT_REVIEW = 'Product review is not found.';

    public const RESPONSE_CODE_PRODUCT_REVIEW_ID_IS_MISSING = '3102';
    public const RESPONSE_DETAIL_PRODUCT_REVIEW_ID_IS_MISSING = 'Product review id is missing.';
}
