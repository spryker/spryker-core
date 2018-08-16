<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CustomersRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_CUSTOMERS = 'customers';

    public const RESPONSE_CODE_CUSTOMER_NOT_FOUND = '402';
    public const RESPONSE_DETAILS_CUSTOMER_NOT_FOUND = 'Customer not found.';
    public const RESPONSE_CODE_CUSTOMER_REFERENCE_MISSING = '405';
    public const RESPONSE_DETAILS_CUSTOMER_REFERENCE_MISSING = 'Customer reference is missing.';

    public const RESOURCE_ADDRESSES = 'addresses';

    public const RESPONSE_CODE_CUSTOMER_ADDRESSES_NOT_FOUND = '403';
    public const RESPONSE_DETAILS_CUSTOMER_ADDRESSES_NOT_FOUND = 'Customer does not have addresses.';

    public const RESPONSE_CODE_ADDRESS_NOT_FOUND = '404';
    public const RESPONSE_DETAILS_ADDRESS_NOT_FOUND = 'Address not found.';

    public const RESOURCE_CUSTOMER_PASSWORD = 'customer-password';
    public const RESPONSE_CODE_PASSWORDS_DONT_MATCH = '406';
    public const RESPONSE_DETAILS_PASSWORDS_DONT_MATCH = 'Passwords don`t match.';
    public const RESPONSE_CODE_PASSWORD_CHANGE_FAILED = '407';
    public const RESPONSE_CODE_INVALID_PASSWORD = '408';
    public const RESPONSE_DETAILS_INVALID_PASSWORD = 'Invalid password';
}
