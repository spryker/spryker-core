<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCustomerPermission\KeyBuilder;

use Spryker\Shared\KeyBuilder\SharedResourceKeyBuilder;
use Spryker\Shared\ProductCustomerPermission\ProductCustomerPermissionConfig;

class ProductCustomerPermissionResourceKeyBuilder extends SharedResourceKeyBuilder
{
    /**
     * @return string
     */
    protected function getResourceType(): string
    {
        return ProductCustomerPermissionConfig::RESOURCE_TYPE_PRODUCT_CUSTOMER_PERMISSION;
    }
}
