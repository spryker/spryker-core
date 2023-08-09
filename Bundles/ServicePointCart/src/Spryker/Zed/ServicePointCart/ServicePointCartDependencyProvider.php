<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointCart;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ServicePointCart\Dependency\Facade\ServicePointCartToCartFacadeBridge;

/**
 * @method \Spryker\Zed\ServicePointCart\ServicePointCartConfig getConfig()
 */
class ServicePointCartDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_CART = 'FACADE_CART';

    /**
     * @var string
     */
    public const PLUGINS_SERVICE_POINT_QUOTE_ITEM_REPLACE_STRATEGY = 'PLUGINS_SERVICE_POINT_QUOTE_ITEM_REPLACE_STRATEGY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addCartFacade($container);
        $container = $this->addServicePointQuoteItemReplaceStrategyPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartFacade(Container $container): Container
    {
        $container->set(static::FACADE_CART, function (Container $container) {
            return new ServicePointCartToCartFacadeBridge($container->getLocator()->cart()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addServicePointQuoteItemReplaceStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SERVICE_POINT_QUOTE_ITEM_REPLACE_STRATEGY, function () {
            return $this->getServicePointQuoteItemReplaceStrategyPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Zed\ServicePointCartExtension\Dependency\Plugin\ServicePointQuoteItemReplaceStrategyPluginInterface>
     */
    protected function getServicePointQuoteItemReplaceStrategyPlugins(): array
    {
        return [];
    }
}
