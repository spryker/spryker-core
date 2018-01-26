<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCustomerPermission;

use Spryker\Client\Kernel\AbstractFactory;

class ProductCustomerPermissionFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductCustomerPermission\Dependency\Client\ProductCustomerPermissionToCustomerClientInterface
     */
    public function getCustomerClient()
    {
        return $this->getProvidedDependency(ProductCustomerPermissionDependencyProvider::CLIENT_CUSTOMER);
    }
}
