<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToProductImageBridge;
use Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToTouchBridge;
use Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToUrlBridge;
use Spryker\Zed\ProductSet\Dependency\QueryContainer\ProductSetToProductImageBridge as ProductSetToProductImageQueryContainerBridge;
use Spryker\Zed\ProductSet\Dependency\QueryContainer\ProductSetToUrlBridge as ProductSetToUrlQueryContainerBridge;

class ProductSetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_TOUCH = 'FACADE_TOUCH';
    public const FACADE_URL = 'FACADE_URL';
    public const FACADE_PRODUCT_IMAGE = 'FACADE_PRODUCT_IMAGE';

    public const QUERY_CONTAINER_URL = 'QUERY_CONTAINER_URL';
    public const QUERY_CONTAINER_PRODUCT_IMAGE = 'QUERY_CONTAINER_PRODUCT_IMAGE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $this->addTouchFacade($container);
        $this->addUrlFacade($container);
        $this->addProductImageFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $this->addUrlQueryContainer($container);
        $this->addProductImageQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addTouchFacade(Container $container)
    {
        $container[static::FACADE_TOUCH] = function (Container $container) {
            return new ProductSetToTouchBridge($container->getLocator()->touch()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addUrlFacade(Container $container)
    {
        $container[static::FACADE_URL] = function (Container $container) {
            return new ProductSetToUrlBridge($container->getLocator()->url()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addProductImageFacade(Container $container)
    {
        $container[static::FACADE_PRODUCT_IMAGE] = function (Container $container) {
            return new ProductSetToProductImageBridge($container->getLocator()->productImage()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addUrlQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_URL] = function (Container $container) {
            return new ProductSetToUrlQueryContainerBridge($container->getLocator()->url()->queryContainer());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addProductImageQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_PRODUCT_IMAGE] = function (Container $container) {
            return new ProductSetToProductImageQueryContainerBridge($container->getLocator()->productImage()->queryContainer());
        };
    }
}
