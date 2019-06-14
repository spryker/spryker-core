<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesSplit;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\SalesSplit\Dependency\Service\SalesSplitToUtilQuantityServiceBridge;

/**
 * @method \Spryker\Zed\SalesSplit\SalesSplitConfig getConfig()
 */
class SalesSplitDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SALES_QUERY_CONTAINER = 'SALES_QUERY_CONTAINER';
    public const SERVICE_UTIL_QUANTITY = 'SERVICE_UTIL_QUANTITY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::SALES_QUERY_CONTAINER] = function (Container $container) {
            return $container->getLocator()->sales()->queryContainer();
        };
        $container = $this->addUtilQuantityService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilQuantityService(Container $container): Container
    {
        $container[static::SERVICE_UTIL_QUANTITY] = function (Container $container) {
            return new SalesSplitToUtilQuantityServiceBridge(
                $container->getLocator()->utilQuantity()->service()
            );
        };

        return $container;
    }
}
