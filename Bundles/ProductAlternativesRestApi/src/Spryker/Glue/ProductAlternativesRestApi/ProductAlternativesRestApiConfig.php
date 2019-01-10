<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAlternativesRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ProductAlternativesRestApiConfig extends AbstractBundleConfig
{
    public const CONTROLLER_ABSTRACT_ALTERNATIVE_PRODUCTS = 'abstract-alternative-products';
    public const CONTROLLER_CONCRETE_ALTERNATIVE_PRODUCTS = 'concrete-alternative-products';
    public const RELATIONSHIP_NAME_ABSTRACT_ALTERNATIVE_PRODUCTS = 'abstract-alternative-products';
    public const RELATIONSHIP_NAME_CONCRETE_ALTERNATIVE_PRODUCTS = 'concrete-alternative-products';

    public const ACTION_ABSTRACT_ALTERNATIVE_PRODUCTS_GET = 'get';
    public const ACTION_CONCRETE_ALTERNATIVE_PRODUCTS_GET = 'get';

    public const RESPONSE_CODE_ALTERNATIVE_PRODUCTS_NOT_FOUND = '315';
    public const RESPONSE_DETAIL_ALTERNATIVE_PRODUCTS_NOT_FOUND = 'Alternative products not found.';
}
