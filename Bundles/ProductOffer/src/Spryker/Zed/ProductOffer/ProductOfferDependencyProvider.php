<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer;

use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductOffer\Dependency\Facade\ProductOfferToMessengerFacadeBridge;
use Spryker\Zed\ProductOffer\Dependency\Facade\ProductOfferToStoreFacadeBridge;

/**
 * @method \Spryker\Zed\ProductOffer\ProductOfferConfig getConfig()
 */
class ProductOfferDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_MESSENGER = 'FACADE_MESSENGER';
    public const FACADE_STORE = 'FACADE_STORE';
    public const PROPEL_QUERY_STORE = 'PROPEL_QUERY_STORE';

    public const PLUGINS_PRODUCT_OFFER_POST_CREATE = 'PLUGINS_PRODUCT_OFFER_POST_CREATE';
    public const PLUGINS_PRODUCT_OFFER_POST_UPDATE = 'PLUGINS_PRODUCT_OFFER_POST_UPDATE';
    public const PLUGINS_PRODUCT_OFFER_EXPANDER = 'PLUGINS_PRODUCT_OFFER_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);

        $container = $this->addStorePropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addMessengerFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addProductOfferPostCreatePlugins($container);
        $container = $this->addProductOfferPostUpdatePlugins($container);
        $container = $this->addProductOfferExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStorePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_STORE, $container->factory(function () {
            return SpyStoreQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMessengerFacade(Container $container): Container
    {
        $container->set(static::FACADE_MESSENGER, $container->factory(function (Container $container) {
            return new ProductOfferToMessengerFacadeBridge($container->getLocator()->messenger()->facade());
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, $container->factory(function (Container $container) {
            return new ProductOfferToStoreFacadeBridge($container->getLocator()->store()->facade());
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOfferPostCreatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_OFFER_POST_CREATE, function () {
            return $this->getProductOfferPostCreatePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOfferPostUpdatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_OFFER_POST_UPDATE, function () {
            return $this->getProductOfferPostUpdatePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOfferExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_OFFER_EXPANDER, function () {
            return $this->getProductOfferExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\ProductOfferExtension\Dependency\Plugin\ProductOfferPostCreatePluginInterface[]
     */
    protected function getProductOfferPostCreatePlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\ProductOfferExtension\Dependency\Plugin\ProductOfferPostUpdatePluginInterface[]
     */
    protected function getProductOfferPostUpdatePlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\ProductOfferExtension\Dependency\Plugin\ProductOfferExpanderPluginInterface[]
     */
    protected function getProductOfferExpanderPlugins(): array
    {
        return [];
    }
}
