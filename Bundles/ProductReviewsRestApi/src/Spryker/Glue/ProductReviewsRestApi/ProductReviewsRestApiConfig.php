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

    public const RESPONSE_DETAIL_RESOURCE_NOT_IMPLEMENTED = 'Resource is not available.';

    /**
     * @uses \Spryker\Glue\ProductsRestApi\ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS
     */
    public const RESOURCE_ABSTRACT_PRODUCTS = 'abstract-products';

    /**
     * @uses \Spryker\Glue\AuthRestApi\AuthRestApiConfig::RESPONSE_CODE_INVALID_OR_MISSING_ACCESS_TOKEN
     */
    public const RESPONSE_CODE_INVALID_OR_MISSING_ACCESS_TOKEN = '005';

    /**
     * @uses \Spryker\Glue\ProductsRestApi\ProductsRestApiConfig::RESPONSE_CODE_CANT_FIND_ABSTRACT_PRODUCT
     */
    public const RESPONSE_CODE_CANT_FIND_ABSTRACT_PRODUCT = '301';

    /**
     * @uses \Spryker\Glue\ProductsRestApi\ProductsRestApiConfig::RESPONSE_CODE_ABSTRACT_PRODUCT_SKU_IS_NOT_SPECIFIED
     */
    public const RESPONSE_CODE_ABSTRACT_PRODUCT_SKU_IS_NOT_SPECIFIED = '311';

    /**
     * @uses \Spryker\Glue\ProductsRestApi\ProductsRestApiConfig::RESPONSE_DETAIL_CANT_FIND_ABSTRACT_PRODUCT
     */
    public const RESPONSE_DETAIL_CANT_FIND_ABSTRACT_PRODUCT = 'Abstract product is not found.';

    /**
     * @uses \Spryker\Glue\ProductsRestApi\ProductsRestApiConfig::RESPONSE_DETAIL_ABSTRACT_PRODUCT_SKU_IS_NOT_SPECIFIED
     */
    public const RESPONSE_DETAIL_ABSTRACT_PRODUCT_SKU_IS_NOT_SPECIFIED = 'Abstract product sku is not specified.';

    protected const MAXIMUM_NUMBER_OF_RESULTS = 10000;

    protected const DEFAULT_REVIEWS_PER_PAGE = 10;

    /**
     * @api
     *
     * @return int
     */
    public function getMaximumNumberOfResults(): int
    {
        return static::MAXIMUM_NUMBER_OF_RESULTS;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getDefaultReviewsPerPage(): int
    {
        return static::DEFAULT_REVIEWS_PER_PAGE;
    }
}
