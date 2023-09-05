<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointAvailabilityCalculator;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculator\Dependency\Client\ProductOfferServicePointAvailabilityCalculatorToProductOfferServicePointAvailabilityClientBridge;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculator\Dependency\Client\ProductOfferServicePointAvailabilityCalculatorToStoreClientBridge;

/**
 * @method \Spryker\Client\ProductOfferServicePointAvailabilityCalculator\ProductOfferServicePointAvailabilityCalculatorConfig getConfig()
 */
class ProductOfferServicePointAvailabilityCalculatorDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY = 'CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY';

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
        $container = $this->addProductOfferServicePointAvailabilityClient($container);
        $container = $this->addStoreClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductOfferServicePointAvailabilityClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY, function (Container $container) {
            return new ProductOfferServicePointAvailabilityCalculatorToProductOfferServicePointAvailabilityClientBridge(
                $container->getLocator()->productOfferServicePointAvailability()->client(),
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
            return new ProductOfferServicePointAvailabilityCalculatorToStoreClientBridge(
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
     * @return list<\Spryker\Client\ProductOfferServicePointAvailabilityCalculatorExtension\Dependency\Plugin\ProductOfferServicePointAvailabilityCalculatorStrategyPluginInterface>
     */
    protected function getProductOfferServicePointAvailabilityCalculatorStrategyPlugins(): array
    {
        return [];
    }
}
