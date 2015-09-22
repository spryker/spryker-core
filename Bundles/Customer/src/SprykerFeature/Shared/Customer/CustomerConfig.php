<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Customer;

use SprykerFeature\Shared\Library\ConfigInterface;

interface CustomerConfig extends ConfigInterface
{

    const CUSTOMER_ANONYMOUS_PATTERN = 'CUSTOMER_ANONYMOUS_PATTERN';
    const CUSTOMER_SECURED_PATTERN = 'CUSTOMER_SECURED_PATTERN';
    const SHOP_MAIL_REGISTRATION_TOKEN = 'SHOP_MAIL_REGISTRATION_TOKEN';
    const SHOP_MAIL_REGISTRATION_SUBJECT = 'SHOP_MAIL_REGISTRATION_SUBJECT';

}
