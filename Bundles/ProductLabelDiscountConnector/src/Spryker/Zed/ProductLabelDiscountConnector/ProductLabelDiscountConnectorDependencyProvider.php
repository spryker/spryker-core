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

    const FACADE_PRODUCT_LABEL = 'FACADE_PRODUCT_LABEL';
    const FACADE_DISCOUNT = 'FACADE_DISCOUNT';

    const QUERY_CONTAINER_PRODUCT_LABEL = 'QUERY_CONTAINER_PRODUCT_LABEL';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $this->provideProductLabelFacade($container);
        $this->provideDiscountFacade($container);

        $this->provideProductLabelQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideProductLabelFacade(Container $container)
    {
        $container[self::FACADE_PRODUCT_LABEL] = function (Container $container) {
            return new ProductLabelDiscountConnectorToProductLabelBridge($container->getLocator()->productLabel()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideDiscountFacade(Container $container)
    {
        $container[self::FACADE_DISCOUNT] = function (Container $container) {
            return new ProductLabelDiscountConnectorToDiscountBridge($container->getLocator()->discount()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideProductLabelQueryContainer(Container $container)
    {
        $container[self::QUERY_CONTAINER_PRODUCT_LABEL] = function (Container $container) {
            return new ProductLabelDiscountConnectorToProductLabelQueryContainerBridge($container->getLocator()->productLabel()->queryContainer());
        };
    }

}
