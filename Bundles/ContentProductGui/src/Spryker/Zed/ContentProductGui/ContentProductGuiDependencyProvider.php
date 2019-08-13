<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductGui;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\ContentProductGui\Dependency\Facade\ContentProductGuiToContentProductBridge;
use Spryker\Zed\ContentProductGui\Dependency\Facade\ContentProductGuiToLocaleBridge;
use Spryker\Zed\ContentProductGui\Dependency\Facade\ContentProductGuiToProductImageBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\ContentProductGui\ContentProductGuiConfig getConfig()
 */
class ContentProductGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_CONTENT_PRODUCT = 'FACADE_CONTENT_PRODUCT';
    public const FACADE_PRODUCT_IMAGE = 'FACADE_PRODUCT_IMAGE';
    public const PROPEL_QUERY_PRODUCT_ABSTRACT = 'PROPEL_QUERY_PRODUCT_ABSTRACT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $this->addProductImageFacade($container);
        $this->addProductQueryContainer($container);
        $this->addLocaleFacade($container);
        $this->addContentProductFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addProductImageFacade(Container $container): void
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
    protected function addProductQueryContainer(Container $container): void
    {
        $container[static::PROPEL_QUERY_PRODUCT_ABSTRACT] = function (Container $container) {
            return SpyProductAbstractQuery::create();
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addLocaleFacade(Container $container): void
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new ContentProductGuiToLocaleBridge($container->getLocator()->locale()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addContentProductFacade(Container $container): void
    {
        $container[static::FACADE_CONTENT_PRODUCT] = function (Container $container) {
            return new ContentProductGuiToContentProductBridge($container->getLocator()->contentProduct()->facade());
        };
    }
}
