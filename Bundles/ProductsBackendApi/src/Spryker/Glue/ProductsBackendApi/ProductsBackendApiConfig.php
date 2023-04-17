<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsBackendApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ProductsBackendApiConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @var string
     */
    public const RESOURCE_PRODUCT_ABSTRACT = 'product-abstract';

    /**
     * @api
     *
     * @var string
     */
    public const RESOURCE_CONCRETE_PRODUCTS = 'concrete-products';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_PRODUCT_EXISTS = '315';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_DETAIL_PRODUCT_EXISTS = 'Product abstract with SKU exists.';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_PRODUCT_NOT_FOUND = '301';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_DETAIL_PRODUCT_NOT_FOUND = 'Product abstract not found.';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_PRODUCT_URL_UNUSABLE = '316';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_DETAIL_PRODUCT_URL_UNUSABLE = 'Product abstract URL cannot be used.';
}
