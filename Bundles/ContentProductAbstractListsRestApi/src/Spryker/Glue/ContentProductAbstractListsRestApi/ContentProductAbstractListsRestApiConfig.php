<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductAbstractListsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ContentProductAbstractListsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_CONTENT_PRODUCTS = 'content-product-abstract-lists';
    public const RESOURCE_CONTENT_PRODUCTS_PRODUCTS = 'products';

    public const CONTROLLER_CONTENT_PRODUCT = 'content-product-abstract-list';
    public const CONTROLLER_CONTENT_PRODUCT_PRODUCTS = 'content-product-abstract-list-products';

    public const RESPONSE_CODE_CONTENT_NOT_FOUND = '2201';
    public const RESPONSE_DETAILS_CONTENT_NOT_FOUND = 'Content not found.';
    public const RESPONSE_CODE_CONTENT_ID_IS_MISSING = '2202';
    public const RESPONSE_DETAILS_CONTENT_ID_IS_MISSING = 'Content id is missing.';
    public const RESPONSE_CODE_CONTENT_TYPE_INVALID = '2203';
    public const RESPONSE_DETAILS_CONTENT_TYPE_INVALID = 'Content type is invalid.';

    public const RESPONSE_DETAIL_RESOURCE_NOT_IMPLEMENTED = 'Endpoint is not implemented.';
}
