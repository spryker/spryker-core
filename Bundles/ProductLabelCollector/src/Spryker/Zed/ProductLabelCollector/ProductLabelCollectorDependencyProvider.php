<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelCollector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductLabelCollector\Dependency\Facade\ProductLabelCollectorToCollectorBridge;
use Spryker\Zed\ProductLabelCollector\Dependency\Facade\ProductLabelCollectorToProductLabelBridge;

class ProductLabelCollectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRODUCT_LABEL = 'FACADE_PRODUCT_LABEL';
    public const FACADE_COLLECTOR = 'FACADE_COLLECTOR';
    public const SERVICE_DATA_READER = 'SERVICE_DATA_READER';
    public const QUERY_CONTAINER_TOUCH = 'QUERY_CONTAINER_TOUCH';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addProductLabelFacade($container);
        $container = $this->addCollectorFacade($container);
        $container = $this->addDataReaderService($container);
        $container = $this->addTouchQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductLabelFacade(Container $container)
    {
        $container[static::FACADE_PRODUCT_LABEL] = function (Container $container) {
            return new ProductLabelCollectorToProductLabelBridge($container->getLocator()->productLabel()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCollectorFacade(Container $container)
    {
        $container[static::FACADE_COLLECTOR] = function (Container $container) {
            return new ProductLabelCollectorToCollectorBridge($container->getLocator()->collector()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDataReaderService(Container $container)
    {
        $container[static::SERVICE_DATA_READER] = function (Container $container) {
            return $container->getLocator()->utilDataReader()->service();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTouchQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_TOUCH] = function (Container $container) {
            return $container->getLocator()->touch()->queryContainer();
        };

        return $container;
    }
}
