<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrdersRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class OrdersRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_ORDERS = 'orders';
    public const RESOURCE_ORDER_ITEMS = 'order-items';
    /**
     * @uses \Spryker\Glue\CustomersRestApi\CustomersRestApiConfig::RESOURCE_CUSTOMERS
     */
    public const RESOURCE_CUSTOMERS = 'customers';

    public const RESOURCE_ORDERS_IS_PROTECTED = true;

    public const RESPONSE_CODE_CANT_FIND_ORDER = '801';
    public const RESPONSE_CODE_CUSTOMER_UNAUTHORIZED = '802';

    public const RESPONSE_DETAIL_CANT_FIND_ORDER = 'Can\'t find order by the given order reference';
    public const RESPONSE_DETAILS_CUSTOMER_UNAUTHORIZED = 'Unauthorized request.';
}
