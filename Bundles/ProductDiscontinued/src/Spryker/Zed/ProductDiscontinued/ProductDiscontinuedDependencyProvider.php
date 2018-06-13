<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductDiscontinued\Dependency\Facade\ProductDiscontinuedToProductFacadeBridge;

class ProductDiscontinuedDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';
    public const PLUGINS_POST_CREATE_PRODUCT_DISCONTINUE = 'PLUGINS_POST_CREATE_PRODUCT_DISCONTINUE';
    public const PLUGINS_POST_DELETE_PRODUCT_DISCONTINUE = 'PLUGINS_POST_DELETE_PRODUCT_DISCONTINUE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $this->addProductFacade($container);
        $this->addPostCreateProductDiscontinuePlugins($container);
        $this->addPostDeleteProductDiscontinuePlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductFacade(Container $container): Container
    {
        $container[static::FACADE_PRODUCT] = function (Container $container) {
            return new ProductDiscontinuedToProductFacadeBridge($container->getLocator()->product()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPostCreateProductDiscontinuePlugins(Container $container): Container
    {
        $container[static::PLUGINS_POST_CREATE_PRODUCT_DISCONTINUE] = function () {
            return $this->getPostCreateProductDiscontinuePlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPostDeleteProductDiscontinuePlugins(Container $container): Container
    {
        $container[static::PLUGINS_POST_DELETE_PRODUCT_DISCONTINUE] = function () {
            return $this->getPostDeleteProductDiscontinuePlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinuedExtension\Dependency\Plugin\PostProductDiscontinuePluginInterface[]
     */
    protected function getPostCreateProductDiscontinuePlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinuedExtension\Dependency\Plugin\PostProductDiscontinuePluginInterface[]
     */
    protected function getPostDeleteProductDiscontinuePlugins(): array
    {
        return [];
    }
}
