<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GiftCardsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class GiftCardsRestApiConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Glue\CartsRestApi\CartsRestApiConfig::RESOURCE_CARTS
     */
    public const RESOURCE_CARTS = 'carts';
    public const RESOURCE_GIFT_CARDS = 'gift-cards';
    
    /**
     * @uses \Spryker\Glue\CartCodesRestApi\CartCodesRestApiConfig::RESOURCE_CART_CODES,
     */
    public const RESOURCE_CART_CODES = 'cart-codes';
}
