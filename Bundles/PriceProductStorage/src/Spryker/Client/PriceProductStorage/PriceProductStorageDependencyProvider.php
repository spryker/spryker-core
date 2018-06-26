<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToPriceProductBridge;
use Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToStorageBridge;
use Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToStoreClientBridge;
use Spryker\Client\PriceProductStorage\Dependency\Service\PriceProductStorageToSynchronizationServiceBridge;

class PriceProductStorageDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_PRICE_PRODUCT = 'CLIENT_PRICE_PRODUCT';
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';
    public const CLIENT_STORE = 'CLIENT_STORE';

    public const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';

    public const PLUGIN_STORAGE_PRICE_DIMENSION = 'PLUGIN_STORAGE_PRICE_DIMENSION';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = $this->addStorageClient($container);
        $container = $this->addPriceProductClient($container);
        $container = $this->addSynchronizationService($container);
        $container = $this->addStoreClient($container);
        $container = $this->addPriceDimensionPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStorageClient(Container $container): Container
    {
        $container[self::CLIENT_STORAGE] = function (Container $container) {
            return new PriceProductStorageToStorageBridge($container->getLocator()->storage()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addPriceProductClient(Container $container): Container
    {
        $container[self::CLIENT_PRICE_PRODUCT] = function (Container $container) {
            return new PriceProductStorageToPriceProductBridge($container->getLocator()->priceProduct()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSynchronizationService(Container $container): Container
    {
        $container[self::SERVICE_SYNCHRONIZATION] = function (Container $container) {
            return new PriceProductStorageToSynchronizationServiceBridge($container->getLocator()->synchronization()->service());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container[static::CLIENT_STORE] = function (Container $container) {
            return new PriceProductStorageToStoreClientBridge($container->getLocator()->store()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function addPriceDimensionPlugins($container): Container
    {
        $container[static::PLUGIN_STORAGE_PRICE_DIMENSION] = function () {
            return $this->getPriceDimensionPlugins();
        };

        return $container;
    }

    /**
     * The plugins are executed during reading prices from Storage
     *
     * @return \Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductStoragePriceDimensionPluginInterface[]
     */
    public function getPriceDimensionPlugins(): array
    {
        return [];
    }
}
