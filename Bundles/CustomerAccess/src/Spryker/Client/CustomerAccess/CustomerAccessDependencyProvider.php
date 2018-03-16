<?php

namespace Spryker\Client\CustomerAccess;

use Spryker\Client\CustomerAccess\Dependency\Client\CustomerAccessToCustomerAccessStorageClientBridge;
use Spryker\Client\CustomerAccess\Dependency\Client\CustomerAccessToCustomerClientBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class CustomerAccessDependencyProvider extends AbstractDependencyProvider
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
            return new CustomerAccessToCustomerClientBridge($container->getLocator()->customer()->client());
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
            return new CustomerAccessToCustomerAccessStorageClientBridge($container->getLocator()->customerAccessStorage()->client());
        };
    }
}
