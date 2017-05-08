<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToLocaleBridge;
use Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToProductLabelBridge;
use Spryker\Zed\ProductLabelGui\Dependency\QueryContainer\ProductLabelGuiToProductLabelQueryContainerBridge;

class ProductLabelGuiDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_LOCALE = 'facade_locale';
    const FACADE_PRODUCT_LABEL = 'facade_product_label';
    const QUERY_CONTAINER_PRODUCT_LABEL = 'query_container_product_label';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->provideLocaleFacade($container);
        $container = $this->provideProductLabelFacade($container);
        $container = $this->provideProductLabelQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideLocaleFacade(Container $container)
    {
        $container[static::FACADE_LOCALE] = function(Container $container) {
            return new ProductLabelGuiToLocaleBridge($container->getLocator()->locale()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideProductLabelFacade(Container $container)
    {
        $container[static::FACADE_PRODUCT_LABEL] = function(Container $container) {
            return $container->getLocator()->productLabel()->facade();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideProductLabelQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_PRODUCT_LABEL] = function(Container $container) {
            return new ProductLabelGuiToProductLabelQueryContainerBridge(
                $container->getLocator()->productLabel()->queryContainer()
            );
        };

        return $container;
    }

}
