<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesOrdersBackendApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class SalesOrdersBackendApiConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Resource for sales orders.
     *
     * @api
     *
     * @var string
     */
    public const RESOURCE_SALES_ORDERS = 'sales-orders';
}
