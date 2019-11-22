<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ProductOptionsRestApiConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Glue\ProductsRestApi\ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS
     */
    public const RESOURCE_ABSTRACT_PRODUCTS = 'abstract-products';

    public const RESOURCE_PRODUCT_OPTIONS = 'product-options';
}
