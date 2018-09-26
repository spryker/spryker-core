<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelDiscountConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductLabelDiscountConnector\Dependency\Facade\ProductLabelDiscountConnectorToDiscountBridge;
use Spryker\Zed\ProductLabelDiscountConnector\Dependency\Facade\ProductLabelDiscountConnectorToProductLabelBridge;
use Spryker\Zed\ProductLabelDiscountConnector\Dependency\QueryContainer\ProductLabelDiscountConnectorToProductLabelBridge as ProductLabelDiscountConnectorToProductLabelQueryContainerBridge;

class ProductLabelDiscountConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRODUCT_LABEL = 'FACADE_PRODUCT_LABEL';
    public const FACADE_DISCOUNT = 'FACADE_DISCOUNT';

    public const QUERY_CONTAINER_PRODUCT_LABEL = 'QUERY_CONTAINER_PRODUCT_LABEL';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $this->addProductLabelFacade($container);
        $this->addDiscountFacade($container);

        $this->addProductLabelQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addProductLabelFacade(Container $container)
    {
        $container[static::FACADE_PRODUCT_LABEL] = function (Container $container) {
            return new ProductLabelDiscountConnectorToProductLabelBridge($container->getLocator()->productLabel()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addDiscountFacade(Container $container)
    {
        $container[static::FACADE_DISCOUNT] = function (Container $container) {
            return new ProductLabelDiscountConnectorToDiscountBridge($container->getLocator()->discount()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addProductLabelQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_PRODUCT_LABEL] = function (Container $container) {
            return new ProductLabelDiscountConnectorToProductLabelQueryContainerBridge($container->getLocator()->productLabel()->queryContainer());
        };
    }
}
