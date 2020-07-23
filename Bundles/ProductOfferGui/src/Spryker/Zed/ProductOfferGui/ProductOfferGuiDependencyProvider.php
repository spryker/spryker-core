<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGui;

use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToLocaleBridge;
use Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToProductOfferBridge;

/**
 * @method \Spryker\Zed\ProductOfferGui\ProductOfferGuiConfig getConfig()
 */
class ProductOfferGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PROPEL_QUERY_PRODUCT_OFFER = 'PROPEL_QUERY_PRODUCT_OFFER';
    public const PLUGINS_PRODUCT_OFFER_LIST_ACTION_VIEW_DATA_EXPANDER = 'PLUGINS_PRODUCT_OFFER_LIST_ACTION_VIEW_DATA_EXPANDER';
    public const PLUGINS_PRODUCT_OFFER_TABLE_EXPANDER = 'PLUGINS_PRODUCT_OFFER_TABLE_EXPANDER';

    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_PRODUCT_OFFER = 'FACADE_PRODUCT_OFFER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addPropelProductOfferQuery($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addProductOfferFacade($container);
        $container = $this->addProductOfferListActionViewDataExpanderPlugins($container);
        $container = $this->addProductOfferTableExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);

        $container = $this->addProductOfferTableExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPropelProductOfferQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_OFFER, $container->factory(function () {
            return SpyProductOfferQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new ProductOfferGuiToLocaleBridge($container->getLocator()->locale()->facade());
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
            return new ProductOfferGuiToProductOfferBridge($container->getLocator()->productOffer()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOfferListActionViewDataExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_OFFER_LIST_ACTION_VIEW_DATA_EXPANDER, function () {
            return $this->getProductOfferListActionViewDataExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiExtension\Dependency\Plugin\ProductOfferListActionViewDataExpanderPluginInterface[]
     */
    protected function getProductOfferListActionViewDataExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOfferTableExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_OFFER_TABLE_EXPANDER, function () {
            return $this->getProductOfferTableExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiExtension\Dependency\Plugin\ProductOfferTableExpanderPluginInterface[]
     */
    protected function getProductOfferTableExpanderPlugins(): array
    {
        return [];
    }
}
