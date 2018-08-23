<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class CustomersRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_CUSTOMERS = 'customers';

    public const RESPONSE_CODE_CUSTOMER_ALREADY_EXISTS = '400';
    public const RESPONSE_CODE_CUSTOMER_CANT_REGISTER_CUSTOMER = '401';

    public const RESPONSE_MESSAGE_CUSTOMER_ALREADY_EXISTS = 'Customer with this email already exists.';
    public const RESPONSE_MESSAGE_CUSTOMER_CANT_REGISTER_CUSTOMER = 'Can`t register a customer.';

    public const RESPONSE_CODE_CUSTOMER_NOT_FOUND = '402';
    public const RESPONSE_DETAILS_CUSTOMER_NOT_FOUND = 'Customer not found.';

    public const RESOURCE_ADDRESSES = 'addresses';

    public const RESPONSE_CODE_CUSTOMER_ADDRESSES_NOT_FOUND = '403';
    public const RESPONSE_DETAILS_CUSTOMER_ADDRESSES_NOT_FOUND = 'Customer does not have addresses.';

    public const RESPONSE_CODE_ADDRESS_NOT_FOUND = '404';
    public const RESPONSE_DETAILS_ADDRESS_NOT_FOUND = 'Address not found.';

    public const RESPONSE_CODE_CUSTOMER_FAILED_TO_SAVE = '406';
    public const RESPONSE_DETAILS_CUSTOMER_FAILED_TO_SAVE = 'Failed to save customer';

    public const RESPONSE_CODE_CUSTOMER_ADDRESS_FAILED_TO_SAVE = '407';
    public const RESPONSE_DETAILS_CUSTOMER_ADDRESS_FAILED_TO_SAVE = 'Failed to save customer address.';
}
