<?php

namespace Spryker\Client\ProductCategoryFilterStorage;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductCategoryFilterStorage\Dependency\Client\ProductCategoryFilterStorageToStorageBridge;
use Spryker\Client\ProductCategoryFilterStorage\Dependency\Service\ProductCategoryFilterStorageToSynchronizationServiceBridge;
use Spryker\Shared\Kernel\Store;

class ProductCategoryFilterStorageDependencyProvider extends AbstractDependencyProvider
{
    const CLIENT_STORAGE = 'CLIENT_STORAGE';
    const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';
    const STORE = 'STORE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addStorageClient($container);
        $container = $this->addSynchronizationService($container);
        $container = $this->addStore($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addStorageClient(Container $container): Container
    {
        $container[self::CLIENT_STORAGE] = function (Container $container) {
            return new ProductCategoryFilterStorageToStorageBridge($container->getLocator()->storage()->client());
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addSynchronizationService(Container $container): Container
    {
        $container[self::SERVICE_SYNCHRONIZATION] = function (Container $container) {
            return new ProductCategoryFilterStorageToSynchronizationServiceBridge($container->getLocator()->synchronization()->service());
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addStore(Container $container): Container
    {
        $container[self::STORE] = function () {
            return Store::getInstance();
        };

        return $container;
    }
}
