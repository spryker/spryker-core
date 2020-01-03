<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductTaxSetsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ProductTaxSetsRestApiConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Glue\ProductsRestApi\ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS
     */
    public const RESOURCE_ABSTRACT_PRODUCTS = 'abstract-products';
    public const RESOURCE_PRODUCT_TAX_SETS = 'product-tax-sets';

    /**
     * @uses \Spryker\Glue\ProductsRestApi\ProductsRestApiConfig::RESPONSE_CODE_CANT_FIND_ABSTRACT_PRODUCT
     */
    public const RESPONSE_CODE_CANT_FIND_ABSTRACT_PRODUCT = '301';
    public const RESPONSE_CODE_CANT_FIND_PRODUCT_TAX_SETS = '310';

    /**
     * @uses \Spryker\Glue\ProductsRestApi\ProductsRestApiConfig::RESPONSE_DETAIL_CANT_FIND_ABSTRACT_PRODUCT
     */
    public const RESPONSE_DETAIL_CANT_FIND_ABSTRACT_PRODUCT = 'Abstract product is not found.';
    public const RESPONSE_DETAIL_CANT_FIND_PRODUCT_TAX_SETS = 'Product tax sets not found.';
}
