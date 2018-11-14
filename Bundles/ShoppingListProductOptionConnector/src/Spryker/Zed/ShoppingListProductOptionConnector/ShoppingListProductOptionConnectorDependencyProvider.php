<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOptionConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ShoppingListProductOptionConnector\Dependency\Facade\ShoppingListProductOptionConnectorToProductOptionFacadeBridge;

class ShoppingListProductOptionConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRODUCT_OPTION = 'FACADE_PRODUCT_OPTION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addProductOptionFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOptionFacade(Container $container): Container
    {
        $container[static::FACADE_PRODUCT_OPTION] = function (Container $container) {
            return new ShoppingListProductOptionConnectorToProductOptionFacadeBridge($container->getLocator()->productOption()->facade());
        };

        return $container;
    }
}
