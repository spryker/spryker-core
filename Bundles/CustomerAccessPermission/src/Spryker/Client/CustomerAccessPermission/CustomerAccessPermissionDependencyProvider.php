<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerAccessPermission;

use Spryker\Client\CustomerAccessPermission\Dependency\Client\CustomerAccessPermissionPermissionToCustomerAccessStorageClientBridge;
use Spryker\Client\CustomerAccessPermission\Dependency\Client\CustomerAccessPermissionPermissionToCustomerClientBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class CustomerAccessPermissionDependencyProvider extends AbstractDependencyProvider
{
    const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';
    const CLIENT_CUSTOMER_ACCESS_STORAGE = 'CLIENT_CUSTOMER_ACCESS_STORAGE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $this->addCustomerClient($container);
        $this->addCustomerAccessStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return void
     */
    protected function addCustomerClient(Container $container)
    {
        $container[static::CLIENT_CUSTOMER] = function (Container $container) {
            return new CustomerAccessPermissionPermissionToCustomerClientBridge($container->getLocator()->customer()->client());
        };
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return void
     */
    protected function addCustomerAccessStorageClient($container)
    {
        $container[static::CLIENT_CUSTOMER_ACCESS_STORAGE] = function (Container $container) {
            return new CustomerAccessPermissionPermissionToCustomerAccessStorageClientBridge($container->getLocator()->customerAccessStorage()->client());
        };
    }
}
