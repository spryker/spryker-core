<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceBridge;

class PriceCartConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_PRICE = 'price facade';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_PRICE] = function (Container $container) {
            return new PriceCartToPriceBridge($container->getLocator()->price()->facade());
        };

        return $container;
    }
}
