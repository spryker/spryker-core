<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointStorage;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToProductOfferFacadeBridge;
use Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToProductOfferServicePointFacadeBridge;
use Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToServicePointFacadeBridge;
use Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToStoreFacadeBridge;

/**
 * @method \Spryker\Zed\ProductOfferServicePointStorage\ProductOfferServicePointStorageConfig getConfig()
 */
class ProductOfferServicePointStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_PRODUCT_OFFER_SERVICE_POINT = 'FACADE_PRODUCT_OFFER_SERVICE_POINT';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_OFFER = 'FACADE_PRODUCT_OFFER';

    /**
     * @var string
     */
    public const FACADE_SERVICE_POINT = 'FACADE_SERVICE_POINT';

    /**
     * @var string
     */
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';

    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @deprecated Use {@link \Spryker\Zed\ProductOfferServicePointStorage\ProductOfferServicePointStorageDependencyProvider::PLUGINS_PRODUCT_OFFER_SERVICE_COLLECTION_STORAGE_FILTER} instead.
     *
     * @var string
     */
    public const PLUGINS_PRODUCT_OFFER_SERVICE_STORAGE_FILTER = 'PLUGINS_PRODUCT_OFFER_SERVICE_STORAGE_FILTER';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_OFFER_SERVICE_COLLECTION_STORAGE_FILTER = 'PLUGINS_PRODUCT_OFFER_SERVICE_COLLECTION_STORAGE_FILTER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addProductOfferServicePointFacade($container);
        $container = $this->addProductOfferFacade($container);
        $container = $this->addServicePointFacade($container);
        $container = $this->addEventBehaviorFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addProductOfferServiceStorageFilterPlugins($container);
        $container = $this->addProductOfferServiceCollectionStorageFilterPlugins($container);

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

        $container = $this->addProductOfferServicePointFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOfferServicePointFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_OFFER_SERVICE_POINT, function (Container $container) {
            return new ProductOfferServicePointStorageToProductOfferServicePointFacadeBridge(
                $container->getLocator()->productOfferServicePoint()->facade(),
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
            return new ProductOfferServicePointStorageToProductOfferFacadeBridge(
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
    protected function addServicePointFacade(Container $container): Container
    {
        $container->set(static::FACADE_SERVICE_POINT, function (Container $container) {
            return new ProductOfferServicePointStorageToServicePointFacadeBridge(
                $container->getLocator()->servicePoint()->facade(),
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
            return new ProductOfferServicePointStorageToEventBehaviorFacadeBridge(
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
            return new ProductOfferServicePointStorageToStoreFacadeBridge(
                $container->getLocator()->store()->facade(),
            );
        });

        return $container;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\ProductOfferServicePointStorage\ProductOfferServicePointStorageDependencyProvider::addProductOfferServiceCollectionStorageFilterPlugins()} instead.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOfferServiceStorageFilterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_OFFER_SERVICE_STORAGE_FILTER, function () {
            return $this->getProductOfferServiceStorageFilterPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOfferServiceCollectionStorageFilterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_OFFER_SERVICE_COLLECTION_STORAGE_FILTER, function () {
            return $this->getProductOfferServiceCollectionStorageFilterPlugins();
        });

        return $container;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\ProductOfferServicePointStorage\ProductOfferServicePointStorageDependencyProvider::getProductOfferServiceCollectionStorageFilterPlugins()} instead.
     *
     * @return list<\Spryker\Zed\ProductOfferServicePointStorageExtension\Dependeency\Plugin\ProductOfferServiceStorageFilterPluginInterface>
     */
    protected function getProductOfferServiceStorageFilterPlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\ProductOfferServicePointStorageExtension\Dependency\Plugin\ProductOfferServiceCollectionStorageFilterPluginInterface>
     */
    protected function getProductOfferServiceCollectionStorageFilterPlugins(): array
    {
        return [];
    }
}
