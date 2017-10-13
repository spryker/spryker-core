<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Price\Dependency\Facade\PriceToProductBridge;
use Spryker\Zed\Price\Dependency\Facade\PriceToTouchBridge;

class PriceDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_TOUCH = 'facade touch';
    const FACADE_PRODUCT = 'product facade';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_TOUCH] = function (Container $container) {
            return new PriceToTouchBridge($container->getLocator()->touch()->facade());
        };

        $container[self::FACADE_PRODUCT] = function (Container $container) {
            return new PriceToProductBridge($container->getLocator()->product()->facade());
        };

        return $container;
    }
}
