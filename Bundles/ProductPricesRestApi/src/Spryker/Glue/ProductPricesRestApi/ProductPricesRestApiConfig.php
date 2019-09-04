<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPricesRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ProductPricesRestApiConfig extends AbstractBundleConfig
{
    protected const PERMISSION_CHECK_ENABLED = false;

    public const RESOURCE_ABSTRACT_PRODUCT_PRICES = 'abstract-product-prices';
    public const RESOURCE_CONCRETE_PRODUCT_PRICES = 'concrete-product-prices';

    public const REQUEST_PARAMETER_CURRENCY = 'currency';
    public const REQUEST_PARAMETER_PRICE_MODE = 'priceMode';

    public const RESPONSE_CODE_ABSTRACT_PRODUCT_PRICES_NOT_FOUND = '307';
    public const RESPONSE_DETAILS_ABSTRACT_PRODUCT_PRICES_NOT_FOUND = 'Can`t find abstract product prices.';
    public const RESPONSE_CODE_CONCRETE_PRODUCT_PRICES_NOT_FOUND = '308';
    public const RESPONSE_DETAILS_CONCRETE_PRODUCT_PRICES_NOT_FOUND = 'Can`t find concrete product prices.';
    public const RESPONSE_CODE_INVALID_CURRENCY = '313';
    public const RESPONSE_DETAILS_INVALID_CURRENCY = 'Currency is invalid.';
    public const RESPONSE_CODE_INVALID_PRICE_MODE = '314';
    public const RESPONSE_DETAILS_INVALID_PRICE_MODE = 'Price mode is invalid.';

    /**
     * @return bool
     */
    public function getPermissionCheckEnabled(): bool
    {
        return static::PERMISSION_CHECK_ENABLED;
    }
}
