<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductGui;

use Spryker\Zed\ContentProductGui\Dependency\Facade\ContentProductGuiToLocaleBridge;
use Spryker\Zed\ContentProductGui\Dependency\Facade\ContentProductGuiToProductImageBridge;
use Spryker\Zed\ContentProductGui\Dependency\QueryContainer\ContentProductGuiToProductBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\ContentProductGui\ContentProductGuiConfig getConfig()
 */
class ContentProductGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_PRODUCT_IMAGE = 'FACADE_PRODUCT_IMAGE';
    public const QUERY_CONTAINER_PRODUCT = 'QUERY_CONTAINER_PRODUCT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $this->provideProductImageFacade($container);
        $this->provideProductQueryContainer($container);
        $this->provideLocaleFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideProductImageFacade(Container $container)
    {
        $container[static::FACADE_PRODUCT_IMAGE] = function (Container $container) {
            return new ContentProductGuiToProductImageBridge($container->getLocator()->productImage()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideProductQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_PRODUCT] = function (Container $container) {
            return new ContentProductGuiToProductBridge($container->getLocator()->product()->queryContainer());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideLocaleFacade(Container $container)
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new ContentProductGuiToLocaleBridge($container->getLocator()->locale()->facade());
        };
    }
}
