<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToPriceProductClientBridge;
use Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToStorageBridge;
use Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToStoreClientBridge;
use Spryker\Client\PriceProductStorage\Dependency\Service\PriceProductStorageToPriceProductServiceBridge;
use Spryker\Client\PriceProductStorage\Dependency\Service\PriceProductStorageToSynchronizationServiceBridge;

/**
 * @method \Spryker\Client\PriceProductStorage\PriceProductStorageConfig getConfig()
 */
class PriceProductStorageDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_PRICE_PRODUCT = 'CLIENT_PRICE_PRODUCT';

    /**
     * @var string
     */
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @var string
     */
    public const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';

    /**
     * @var string
     */
    public const SERVICE_PRICE_PRODUCT = 'SERVICE_PRICE_PRODUCT';

    /**
     * @var string
     */
    public const PLUGIN_STORAGE_PRICE_DIMENSION = 'PLUGIN_STORAGE_PRICE_DIMENSION';

    /**
     * @var string
     */
    public const PLUGIN_PRICE_PRODUCT_PRICES_EXTRACTOR = 'PLUGIN_PRICE_PRODUCT_PRICES_EXTRACTOR';

    /**
     * @var string
     */
    public const PLUGIN_PRICE_PRODUCT_FILTER_EXPANDER = 'PLUGIN_PRICE_PRODUCT_FILTER_EXPANDER';

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
        $container = $this->addPriceProductService($container);
        $container = $this->addStoreClient($container);
        $container = $this->addPriceDimensionPlugins($container);
        $container = $this->addPriceProductPricesExtractorPlugins($container);
        $container = $this->addPriceProductFilterExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORAGE, function (Container $container) {
            return new PriceProductStorageToStorageBridge($container->getLocator()->storage()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addPriceProductClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRICE_PRODUCT, function (Container $container) {
            return new PriceProductStorageToPriceProductClientBridge($container->getLocator()->priceProduct()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSynchronizationService(Container $container): Container
    {
        $container->set(static::SERVICE_SYNCHRONIZATION, function (Container $container) {
            return new PriceProductStorageToSynchronizationServiceBridge($container->getLocator()->synchronization()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addPriceProductService(Container $container): Container
    {
        $container->set(static::SERVICE_PRICE_PRODUCT, function (Container $container) {
            return new PriceProductStorageToPriceProductServiceBridge($container->getLocator()->priceProduct()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container) {
            return new PriceProductStorageToStoreClientBridge($container->getLocator()->store()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function addPriceDimensionPlugins($container): Container
    {
        $container->set(static::PLUGIN_STORAGE_PRICE_DIMENSION, function () {
            return $this->getPriceDimensionStorageReaderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductStoragePriceDimensionPluginInterface>
     */
    public function getPriceDimensionStorageReaderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addPriceProductPricesExtractorPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_PRICE_PRODUCT_PRICES_EXTRACTOR, function () {
            return $this->getPriceProductPricesExtractorPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductStoragePricesExtractorPluginInterface>
     */
    protected function getPriceProductPricesExtractorPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addPriceProductFilterExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_PRICE_PRODUCT_FILTER_EXPANDER, function () {
            return $this->getPriceProductFilterExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductFilterExpanderPluginInterface>
     */
    protected function getPriceProductFilterExpanderPlugins(): array
    {
        return [];
    }
}
