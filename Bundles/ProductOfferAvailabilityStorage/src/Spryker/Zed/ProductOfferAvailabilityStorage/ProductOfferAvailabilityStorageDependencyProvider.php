<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailabilityStorage;

use Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStockQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Facade\ProductOfferAvailabilityStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Facade\ProductOfferAvailabilityStorageToProductOfferAvailabilityFacadeBridge;
use Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Facade\ProductOfferAvailabilityStorageToProductOfferFacadeBridge;
use Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Service\ProductOfferAvailabilityStorageToSynchronizationServiceBridge;

/**
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageConfig getConfig()
 */
class ProductOfferAvailabilityStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRODUCT_OFFER = 'FACADE_PRODUCT_OFFER';
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const FACADE_PRODUCT_OFFER_AVAILABILITY_FACADE = 'FACADE_PRODUCT_OFFER_AVAILABILITY';

    public const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';

    public const PROPEL_QUERY_PRODUCT_OFFER_STOCK = 'PROPEL_QUERY_PRODUCT_OFFER_STOCK';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);

        $container = $this->addProductOfferStockPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addEventBehaviorFacade($container);
        $container = $this->addProductOfferAvailabilityFacade($container);

        $container = $this->addSynchronizationService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addProductOfferFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOfferStockPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_OFFER_STOCK, $container->factory(function () {
            return SpyProductOfferStockQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventBehaviorFacade(Container $container): Container
    {
        $container->set(static::FACADE_EVENT_BEHAVIOR, function (Container $container) {
            return new ProductOfferAvailabilityStorageToEventBehaviorFacadeBridge(
                $container->getLocator()->eventBehavior()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOfferFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_OFFER, function (Container $container) {
            return new ProductOfferAvailabilityStorageToProductOfferFacadeBridge(
                $container->getLocator()->productOffer()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSynchronizationService(Container $container): Container
    {
        $container->set(static::SERVICE_SYNCHRONIZATION, function (Container $container) {
            return new ProductOfferAvailabilityStorageToSynchronizationServiceBridge(
                $container->getLocator()->synchronization()->service()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOfferAvailabilityFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_OFFER_AVAILABILITY_FACADE, function (Container $container) {
            return new ProductOfferAvailabilityStorageToProductOfferAvailabilityFacadeBridge(
                $container->getLocator()->productOfferAvailability()->facade()
            );
        });

        return $container;
    }
}
