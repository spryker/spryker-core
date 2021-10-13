<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeBridge;

/**
 * @method \Spryker\Zed\ShoppingListsRestApi\ShoppingListsRestApiConfig getConfig()
 */
class ShoppingListsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_SHOPPING_LIST = 'FACADE_SHOPPING_LIST';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addShoppingListFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addShoppingListFacade(Container $container): Container
    {
        $container->set(static::FACADE_SHOPPING_LIST, function (Container $container) {
            return new ShoppingListsRestApiToShoppingListFacadeBridge(
                $container->getLocator()->shoppingList()->facade()
            );
        });

        return $container;
    }
}
