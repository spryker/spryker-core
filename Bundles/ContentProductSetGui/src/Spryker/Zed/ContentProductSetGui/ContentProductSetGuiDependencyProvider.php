<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductSetGui;

use Orm\Zed\ProductSet\Persistence\SpyProductSetQuery;
use Spryker\Zed\ContentProductSetGui\Dependency\Facade\ContentProductSetGuiToLocaleBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\ContentProductSetGui\ContentProductSetGuiConfig getConfig()
 */
class ContentProductSetGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const PROPEL_QUERY_PRODUCT_SET = 'PROPEL_QUERY_PRODUCT_SET';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $this->addProductQueryContainer($container);
        $this->addLocaleFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addProductQueryContainer(Container $container): void
    {
        $container[static::PROPEL_QUERY_PRODUCT_SET] = function () {
            return SpyProductSetQuery::create();
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
            return new ContentProductSetGuiToLocaleBridge($container->getLocator()->locale()->facade());
        };
    }
}
