<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerAccessPermission;

use Spryker\Client\CustomerAccessPermission\CustomerAccess\CustomerAccess;
use Spryker\Client\CustomerAccessPermission\CustomerAccess\CustomerAccessInterface;
use Spryker\Client\CustomerAccessPermission\Dependency\Client\CustomerAccessPermissionToCustomerAccessStorageClientInterface;
use Spryker\Client\CustomerAccessPermission\Dependency\Client\CustomerAccessPermissionToCustomerClientInterface;
use Spryker\Client\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\CustomerAccessPermission\CustomerAccessPermissionConfig getConfig()
 */
class CustomerAccessPermissionFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CustomerAccessPermission\CustomerAccess\CustomerAccessInterface
     */
    public function createCustomerAccess(): CustomerAccessInterface
    {
        return new CustomerAccess($this->getCustomerAccessStorageClient(), $this->getConfig());
    }

    /**
     * @return \Spryker\Client\CustomerAccessPermission\Dependency\Client\CustomerAccessPermissionToCustomerClientInterface
     */
    public function getCustomerClient(): CustomerAccessPermissionToCustomerClientInterface
    {
        return $this->getProvidedDependency(CustomerAccessPermissionDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Client\CustomerAccessPermission\Dependency\Client\CustomerAccessPermissionToCustomerAccessStorageClientInterface
     */
    public function getCustomerAccessStorageClient(): CustomerAccessPermissionToCustomerAccessStorageClientInterface
    {
        return $this->getProvidedDependency(CustomerAccessPermissionDependencyProvider::CLIENT_CUSTOMER_ACCESS_STORAGE);
    }
}
