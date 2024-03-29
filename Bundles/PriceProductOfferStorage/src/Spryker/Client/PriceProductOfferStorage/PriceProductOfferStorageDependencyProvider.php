<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductOfferStorage;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\PriceProductOfferStorage\Dependency\Client\PriceProductOfferStorageToPriceProductStorageClientBridge;
use Spryker\Client\PriceProductOfferStorage\Dependency\Client\PriceProductOfferStorageToStorageClientBridge;
use Spryker\Client\PriceProductOfferStorage\Dependency\Client\PriceProductOfferStorageToStoreClientBridge;
use Spryker\Client\PriceProductOfferStorage\Dependency\Service\PriceProductOfferStorageToPriceProductServiceBridge;
use Spryker\Client\PriceProductOfferStorage\Dependency\Service\PriceProductOfferStorageToSynchronizationServiceBridge;

class PriceProductOfferStorageDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';

    /**
     * @var string
     */
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';

    /**
     * @var string
     */
    public const FACADE_STORE_CLIENT = 'FACADE_STORE_CLIENT';

    /**
     * @var string
     */
    public const FACADE_PRICE_PRODUCT_SERVICE = 'FACADE_PRICE_PRODUCT_SERVICE';

    /**
     * @var string
     */
    public const PLUGINS_PRICE_PRODUCT_OFFER_STORAGE_PRICE_EXTRACTOR = 'PLUGINS_PRICE_PRODUCT_OFFER_STORAGE_PRICE_EXTRACTOR';

    /**
     * @var string
     */
    public const CLIENT_PRICE_PRODUCT_STORAGE = 'CLIENT_PRICE_PRODUCT_STORAGE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container = $this->addSynchronizationService($container);
        $container = $this->addStorageClient($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addPriceProductService($container);
        $container = $this->addPriceProductOfferStoragePriceExtractorPlugins($container);
        $container = $this->addPriceProductStorageClient($container);

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
            return new PriceProductOfferStorageToSynchronizationServiceBridge($container->getLocator()->synchronization()->service());
        });

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
            return new PriceProductOfferStorageToStorageClientBridge($container->getLocator()->storage()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE_CLIENT, function (Container $container) {
            return new PriceProductOfferStorageToStoreClientBridge($container->getLocator()->store()->client());
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
        $container->set(static::FACADE_PRICE_PRODUCT_SERVICE, function (Container $container) {
            return new PriceProductOfferStorageToPriceProductServiceBridge($container->getLocator()->priceProduct()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addPriceProductOfferStoragePriceExtractorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRICE_PRODUCT_OFFER_STORAGE_PRICE_EXTRACTOR, function () {
            return $this->getPriceProductOfferStoragePriceExtractorPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Client\PriceProductOfferStorageExtension\Dependency\Plugin\PriceProductOfferStoragePriceExtractorPluginInterface>
     */
    protected function getPriceProductOfferStoragePriceExtractorPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addPriceProductStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRICE_PRODUCT_STORAGE, function (Container $container) {
            return new PriceProductOfferStorageToPriceProductStorageClientBridge(
                $container->getLocator()->priceProductStorage()->client(),
            );
        });

        return $container;
    }
}
