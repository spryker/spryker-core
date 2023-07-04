<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeStorage;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade\ProductOfferShipmentTypeStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade\ProductOfferShipmentTypeStorageToProductOfferFacadeBridge;
use Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade\ProductOfferShipmentTypeStorageToProductOfferShipmentTypeFacadeBridge;
use Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade\ProductOfferShipmentTypeStorageToStoreFacadeBridge;

/**
 * @method \Spryker\Zed\ProductOfferShipmentTypeStorage\ProductOfferShipmentTypeStorageConfig getConfig()
 */
class ProductOfferShipmentTypeStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_PRODUCT_OFFER_SHIPMENT_TYPE = 'FACADE_PRODUCT_OFFER_SHIPMENT_TYPE';

    /**
     * @var string
     */
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';

    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_OFFER = 'FACADE_PRODUCT_OFFER';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_OFFER_SHIPMENT_TYPE_STORAGE_FILTER = 'PLUGINS_PRODUCT_OFFER_SHIPMENT_TYPE_STORAGE_FILTER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addProductOfferShipmentTypeFacade($container);
        $container = $this->addProductOfferFacade($container);
        $container = $this->addEventBehaviorFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addProductOfferShipmentTypeStorageFilterPlugins($container);

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
        $container = $this->addProductOfferShipmentTypeFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOfferShipmentTypeFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_OFFER_SHIPMENT_TYPE, function (Container $container) {
            return new ProductOfferShipmentTypeStorageToProductOfferShipmentTypeFacadeBridge(
                $container->getLocator()->productOfferShipmentType()->facade(),
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
            return new ProductOfferShipmentTypeStorageToProductOfferFacadeBridge(
                $container->getLocator()->productOffer()->facade(),
            );
        });

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
            return new ProductOfferShipmentTypeStorageToEventBehaviorFacadeBridge(
                $container->getLocator()->eventBehavior()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            return new ProductOfferShipmentTypeStorageToStoreFacadeBridge(
                $container->getLocator()->store()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOfferShipmentTypeStorageFilterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_OFFER_SHIPMENT_TYPE_STORAGE_FILTER, function () {
            return $this->getProductOfferShipmentTypeStorageFilterPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Zed\ProductOfferShipmentTypeStorageExtension\Dependency\Plugin\ProductOfferShipmentTypeStorageFilterPluginInterface>
     */
    protected function getProductOfferShipmentTypeStorageFilterPlugins(): array
    {
        return [];
    }
}
