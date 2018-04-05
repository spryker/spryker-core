<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToPermissionFacadeBridge;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeBridge;

class ShoppingListDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';
    public const FACADE_PERMISSION = 'FACADE_PERMISSION';
    public const PLUGINS_ITEM_EXPANDER = 'PLUGINS_ITEM_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addProductFacade($container);
        $container = $this->addPermissionFacade($container);
        $container = $this->addItemExpanderPlugins($container);

        return $container;
    }

    /**
     * @return \Spryker\Zed\ShoppingList\Dependency\Plugin\ItemExpanderPluginInterface[]
     */
    protected function getItemExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductFacade(Container $container): Container
    {
        $container[static::FACADE_PRODUCT] = function (Container $container) {
            return new ShoppingListToProductFacadeBridge($container->getLocator()->product()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPermissionFacade(Container $container): Container
    {
        $container[static::FACADE_PERMISSION] = function (Container $container) {
            return new ShoppingListToPermissionFacadeBridge($container->getLocator()->permission()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addItemExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_ITEM_EXPANDER] = function () {
            return $this->getItemExpanderPlugins();
        };

        return $container;
    }
}
