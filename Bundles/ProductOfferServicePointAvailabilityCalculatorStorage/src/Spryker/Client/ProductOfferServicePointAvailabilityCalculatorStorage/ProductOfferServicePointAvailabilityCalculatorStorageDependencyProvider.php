<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Dependency\Client\ProductOfferServicePointAvailabilityCalculatorStorageToProductOfferServicePointAvailabilityStorageClientBridge;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Dependency\Client\ProductOfferServicePointAvailabilityCalculatorStorageToStoreClientBridge;

/**
 * @method \Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\ProductOfferServicePointAvailabilityCalculatorStorageConfig getConfig()
 */
class ProductOfferServicePointAvailabilityCalculatorStorageDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_STORAGE = 'CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_CALCULATOR_STRATEGY = 'PLUGINS_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_CALCULATOR_STRATEGY';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container = $this->addProductOfferServicePointAvailabilityCalculatorStrategyPlugins($container);
        $container = $this->addProductOfferServicePointAvailabilityStorageClient($container);
        $container = $this->addStoreClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductOfferServicePointAvailabilityStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_STORAGE, function (Container $container) {
            return new ProductOfferServicePointAvailabilityCalculatorStorageToProductOfferServicePointAvailabilityStorageClientBridge(
                $container->getLocator()->productOfferServicePointAvailabilityStorage()->client(),
            );
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
            return new ProductOfferServicePointAvailabilityCalculatorStorageToStoreClientBridge(
                $container->getLocator()->store()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductOfferServicePointAvailabilityCalculatorStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_CALCULATOR_STRATEGY, function () {
            return $this->getProductOfferServicePointAvailabilityCalculatorStrategyPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorageExtension\Dependency\Plugin\ProductOfferServicePointAvailabilityCalculatorStrategyPluginInterface>
     */
    protected function getProductOfferServicePointAvailabilityCalculatorStrategyPlugins(): array
    {
        return [];
    }
}
