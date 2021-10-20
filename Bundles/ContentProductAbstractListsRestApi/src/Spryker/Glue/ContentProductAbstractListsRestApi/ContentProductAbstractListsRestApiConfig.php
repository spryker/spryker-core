<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductAbstractListsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ContentProductAbstractListsRestApiConfig extends AbstractBundleConfig
{
    /**
     * @deprecated Will be removed in the next major release.
     * @var string
     */
    public const ACTION_RESOURCE_CONTENT_PRODUCT_ABSTRACT_LISTS_GET = 'get';

    /**
     * @var string
     */
    public const RESOURCE_CONTENT_PRODUCT_ABSTRACT_LISTS = 'content-product-abstract-lists';

    /**
     * @deprecated Will be removed in the next major release.
     * @var string
     */
    public const RESOURCE_CONTENT_PRODUCT_ABSTRACT_LISTS_PRODUCTS = 'content-product-abstract';

    /**
     * @see \Spryker\Glue\ProductsRestApi\ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS
     * @var string
     */
    public const RESOURCE_ABSTRACT_PRODUCTS = 'abstract-products';

    /**
     * @deprecated Will be removed in the next major release.
     * @var string
     */
    public const CONTROLLER_CONTENT_PRODUCT_ABSTRACT_LIST = 'content-product-abstract-list';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CONTENT_NOT_FOUND = '2201';

    /**
     * @var string
     */
    public const RESPONSE_DETAILS_CONTENT_NOT_FOUND = 'Content item not found.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CONTENT_KEY_IS_MISSING = '2202';

    /**
     * @var string
     */
    public const RESPONSE_DETAILS_CONTENT_KEY_IS_MISSING = 'Content key is missing.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CONTENT_TYPE_INVALID = '2203';

    /**
     * @var string
     */
    public const RESPONSE_DETAILS_CONTENT_TYPE_INVALID = 'Content type is invalid.';
}
