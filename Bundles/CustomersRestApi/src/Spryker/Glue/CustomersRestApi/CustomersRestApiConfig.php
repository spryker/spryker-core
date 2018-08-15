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

    public const RESOURCE_ADDRESSES = 'addresses';

    public const RESPONSE_CODE_CUSTOMER_ADDRESS_FAILED_TO_SAVE = '407';
    public const RESPONSE_DETAILS_CUSTOMER_ADDRESS_FAILED_TO_SAVE = 'Failed to save customer address.';
}
