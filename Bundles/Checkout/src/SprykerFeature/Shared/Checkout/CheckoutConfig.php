<?php

namespace SprykerFeature\Shared\Checkout;

use SprykerFeature\Shared\Library\ConfigInterface;

class CheckoutConfig implements ConfigInterface
{

    const ERROR_CODE_CUSTOMER_ALREADY_REGISTERED = 4001;
    const ERROR_CODE_PRODUCT_UNAVAILABLE = 4002;
    const ERROR_CODE_CART_AMOUNT_DIFFERENT = 4003;
    const ERROR_CODE_UNKNOWN_ERROR = 5000;

}
