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
     * @uses \Spryker\Shared\ContentProduct\ContentProductConfig::TWIG_FUNCTION_NAME
     */
    public const TWIG_FUNCTION_NAME = 'content_product_abstract_list';

    public const ACTION_RESOURCE_CONTENT_PRODUCT_ABSTRACT_LISTS_GET = 'get';

    public const RESOURCE_CONTENT_PRODUCT_ABSTRACT_LISTS = 'content-product-abstract-lists';
    public const RESOURCE_CONTENT_PRODUCT_ABSTRACT_LISTS_PRODUCTS = 'content-product-abstract';

    public const CONTROLLER_CONTENT_PRODUCT_ABSTRACT_LIST = 'content-product-abstract-list';

    public const RESPONSE_CODE_CONTENT_NOT_FOUND = '2201';
    public const RESPONSE_DETAILS_CONTENT_NOT_FOUND = 'Content item not found.';
    public const RESPONSE_CODE_CONTENT_KEY_IS_MISSING = '2202';
    public const RESPONSE_DETAILS_CONTENT_KEY_IS_MISSING = 'Content key is missing.';
    public const RESPONSE_CODE_CONTENT_TYPE_INVALID = '2203';
    public const RESPONSE_DETAILS_CONTENT_TYPE_INVALID = 'Content type is invalid.';
}
