<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CheckoutRestApi;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class CheckoutRestApiConfig extends AbstractBundleConfig
{
    public const RESPONSE_CODE_CHECKOUT_DATA_INVALID = '1101';
    public const RESPONSE_CODE_ORDER_NOT_PLACED = '1102';
    public const RESPONSE_CODE_CART_NOT_FOUND = '1103';
    public const RESPONSE_CODE_CART_IS_EMPTY = '1104';
    public const RESPONSE_CODE_UNABLE_TO_DELETE_CART = '1106';

    public const RESPONSE_DETAILS_CHECKOUT_DATA_INVALID = 'Checkout data is invalid.';
    public const RESPONSE_DETAILS_ORDER_NOT_PLACED = 'Order could not be placed.';
    public const RESPONSE_DETAILS_CART_NOT_FOUND = 'Cart not found.';
    public const RESPONSE_DETAILS_CART_IS_EMPTY = 'Cart is empty.';
    public const RESPONSE_DETAILS_UNABLE_TO_DELETE_CART = 'Unable to delete cart.';
}
