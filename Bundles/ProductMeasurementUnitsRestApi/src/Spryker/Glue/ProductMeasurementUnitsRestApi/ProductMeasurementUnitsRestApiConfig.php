<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductMeasurementUnitsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ProductMeasurementUnitsRestApiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const RESOURCE_PRODUCT_MEASUREMENT_UNITS = 'product-measurement-units';

    /**
     * @var string
     */
    public const RESOURCE_SALES_UNITS = 'sales-units';

    /**
     * @uses \Spryker\Glue\ProductsRestApi\ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS
     *
     * @var string
     */
    public const RESOURCE_CONCRETE_PRODUCTS = 'concrete-products';

    /**
     * @uses \Spryker\Glue\ProductsRestApi\ProductsRestApiConfig::RESPONSE_CODE_CANT_FIND_CONCRETE_PRODUCT
     *
     * @var string
     */
    public const RESPONSE_CODE_CANT_FIND_CONCRETE_PRODUCT = '302';

    /**
     * @uses \Spryker\Glue\ProductsRestApi\ProductsRestApiConfig::RESPONSE_DETAIL_CANT_FIND_CONCRETE_PRODUCT
     *
     * @var string
     */
    public const RESPONSE_DETAIL_CANT_FIND_CONCRETE_PRODUCT = 'Concrete product is not found.';

    /**
     * @uses \Spryker\Glue\ProductsRestApi\ProductsRestApiConfig::RESPONSE_CODE_CONCRETE_PRODUCT_SKU_IS_NOT_SPECIFIED
     *
     * @var string
     */
    public const RESPONSE_CODE_CONCRETE_PRODUCT_SKU_IS_NOT_SPECIFIED = '312';

    /**
     * @uses \Spryker\Glue\ProductsRestApi\ProductsRestApiConfig::RESPONSE_DETAIL_CONCRETE_PRODUCT_SKU_IS_NOT_SPECIFIED
     *
     * @var string
     */
    public const RESPONSE_DETAIL_CONCRETE_PRODUCT_SKU_IS_NOT_SPECIFIED = 'Concrete product sku is not specified.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_PRODUCT_MEASUREMENT_UNIT_CODE_IS_NOT_SPECIFIED = '3401';

    /**
     * @var string
     */
    public const RESPONSE_CODE_PRODUCT_MEASUREMENT_UNIT_NOT_FOUND = '3402';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_MEASUREMENT_UNIT_CODE_IS_NOT_SPECIFIED = 'Product measurement unit code has not been specified.';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_PRODUCT_MEASUREMENT_UNIT_NOT_FOUND = 'Product measurement unit not found.';
}
