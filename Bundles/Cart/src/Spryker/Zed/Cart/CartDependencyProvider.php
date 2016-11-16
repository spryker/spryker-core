<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart;

use Spryker\Zed\Cart\Dependency\Facade\CartToCalculationBridge;
use Spryker\Zed\Cart\Dependency\Facade\CartToMessengerBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CartDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_CALCULATION = 'calculation facade';
    const FACADE_MESSENGER = 'messenger facade';
    const CART_EXPANDER_PLUGINS = 'cart expander plugins';
    const CART_PRE_CHECK_PLUGINS = 'pre check plugins';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_CALCULATION] = function (Container $container) {
            return new CartToCalculationBridge($container->getLocator()->calculation()->facade());
        };

        $container[self::FACADE_MESSENGER] = function (Container $container) {
            return new CartToMessengerBridge($container->getLocator()->messenger()->facade());
        };

        $container[self::CART_EXPANDER_PLUGINS] = function (Container $container) {
            return $this->getExpanderPlugins($container);
        };

        $container[self::CART_PRE_CHECK_PLUGINS] = function (Container $container) {
            return $this->getCartPreCheckPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface[]
     */
    protected function getExpanderPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $containter
     *
     * @return \Spryker\Zed\Cart\Dependency\CartPreCheckPluginInterface[]
     */
    protected function getCartPreCheckPlugins(Container $containter)
    {
        return [];
    }

}
