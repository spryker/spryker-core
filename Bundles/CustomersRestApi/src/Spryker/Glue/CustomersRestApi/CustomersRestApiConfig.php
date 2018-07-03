<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class CustomersRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_REGISTER_CUSTOMERS = 'register-customer';

    public const RESPONSE_CODE_CUSTOMER_ALREADY_EXISTS = '500';
    public const RESPONSE_CODE_CUSTOMER_CANT_REGISTER_CUSTOMER = '501';

    public const RESPONSE_MESSAGE_CUSTOMER_ALREADY_EXISTS = 'Customer with this email already exists.';
    public const RESPONSE_MESSAGE_CUSTOMER_CANT_REGISTER_CUSTOMER = 'Can`t register customer.';
}
