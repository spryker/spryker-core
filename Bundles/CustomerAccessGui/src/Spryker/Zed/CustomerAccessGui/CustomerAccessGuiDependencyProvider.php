<?php

namespace Spryker\Zed\CustomerAccessGui;

use Spryker\Zed\CustomerAccessGui\Dependency\Facade\CustomerAccessGuiToCustomerAccessFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CustomerAccessGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_CUSTOMER_ACCESS = 'FACADE_CUSTOMER_ACCESS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $this->addCustomerAccessFacade($container);
        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addCustomerAccessFacade(Container $container)
    {
        $container[static::FACADE_CUSTOMER_ACCESS] = function (Container $container) {
            return new CustomerAccessGuiToCustomerAccessFacadeBridge($container->getLocator()->customerAccess()->facade());
        };
    }
}