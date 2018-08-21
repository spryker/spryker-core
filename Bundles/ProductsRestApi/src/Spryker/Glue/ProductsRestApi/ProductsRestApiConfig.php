<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ProductsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_ABSTRACT_PRODUCTS = 'abstract-products';
    public const RESPONSE_CODE_CANT_FIND_ABSTRACT_PRODUCT = '301';
    public const RESPONSE_DETAIL_CANT_FIND_ABSTRACT_PRODUCT = 'Abstract product is not found.';

    public const RESOURCE_CONCRETE_PRODUCTS = 'concrete-products';
    public const RESPONSE_CODE_CANT_FIND_CONCRETE_PRODUCT = '302';
    public const RESPONSE_DETAIL_CANT_FIND_CONCRETE_PRODUCT = 'Concrete product is not found.';
}
