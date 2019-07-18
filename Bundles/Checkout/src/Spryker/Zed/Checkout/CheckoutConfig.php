<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Checkout;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CheckoutConfig extends AbstractBundleConfig
{
    public const ERROR_CODE_CUSTOMER_ALREADY_REGISTERED = 4001;
    public const ERROR_CODE_PRODUCT_UNAVAILABLE = 4002;
    public const ERROR_CODE_CART_AMOUNT_DIFFERENT = '4003';
}
