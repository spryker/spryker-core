<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCustomerPermission;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductCustomerPermissionConfig extends AbstractBundleConfig
{
    const RESOURCE_TYPE_PRODUCT_CUSTOMER_PERMISSION = 'product_customer_permission';

    const ELASTICSEARCH_INDEX_TYPE_NAME = 'customer-page';
}
