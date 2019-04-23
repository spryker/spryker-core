<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductSetGui;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\ContentProductSetGui\Dependency\Facade\ContentProductSetGuiToContentProductBridge;
use Spryker\Zed\ContentProductSetGui\Dependency\Facade\ContentProductSetGuiToLocaleBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class ContentProductSetGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_CONTENT_PRODUCT = 'FACADE_CONTENT_PRODUCT';
    public const PROPEL_QUERY_PRODUCT_ABSTRACT = 'PROPEL_QUERY_PRODUCT_ABSTRACT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $this->provideProductQueryContainer($container);
        $this->provideLocaleFacade($container);
        $this->provideContentProductFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideProductQueryContainer(Container $container): void
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
    protected function provideLocaleFacade(Container $container): void
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new ContentProductSetGuiToLocaleBridge($container->getLocator()->locale()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideContentProductFacade(Container $container): void
    {
        $container[static::FACADE_CONTENT_PRODUCT] = function (Container $container) {
            return new ContentProductSetGuiToContentProductBridge($container->getLocator()->contentProduct()->facade());
        };
    }
}
