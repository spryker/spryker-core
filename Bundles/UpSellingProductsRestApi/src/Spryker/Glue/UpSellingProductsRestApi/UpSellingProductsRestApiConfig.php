<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UpSellingProductsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class UpSellingProductsRestApiConfig extends AbstractBundleConfig
{
    public const RELATIONSHIP_NAME_UP_SELLING_PRODUCTS = 'up-selling-products';
    public const CONTROLLER_CART_UP_SELLING_PRODUCTS = 'cart-up-selling-products';
    public const CONTROLLER_GUEST_CART_UP_SELLING_PRODUCTS = 'guest-cart-up-selling-products';
    public const ACTION_UP_SELLING_PRODUCTS_GET = 'get';
}
