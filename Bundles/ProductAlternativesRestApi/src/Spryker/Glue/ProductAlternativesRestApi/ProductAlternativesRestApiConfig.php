<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAlternativesRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ProductAlternativesRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_ALTERNATIVE_PRODUCTS = 'alternative-products';
    public const CONTROLLER_ALTERNATIVE_PRODUCTS = 'alternative-products-resource';

    public const ACTION_ALTERNATIVE_PRODUCTS_GET = 'get';

    public const RESPONSE_CODE_ALTERNATIVE_PRODUCTS_NOT_FOUND = '315';
    public const RESPONSE_DETAIL_ALTERNATIVE_PRODUCTS_NOT_FOUND = 'Alternative products not found.';
}
