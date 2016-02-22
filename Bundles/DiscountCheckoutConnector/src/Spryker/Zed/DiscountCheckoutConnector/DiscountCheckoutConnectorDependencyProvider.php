<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountCheckoutConnector;

use Spryker\Zed\DiscountCheckoutConnector\Dependency\Facade\DiscountCheckoutConnectorToDiscountBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class DiscountCheckoutConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const QUERY_CONTAINER_DISCOUNT = 'discount query container';
    const FACADE_DISCOUNT = 'facade discount';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::QUERY_CONTAINER_DISCOUNT] = function (Container $container) {
            return $container->getLocator()->discount()->queryContainer();
        };

        $container[self::FACADE_DISCOUNT] = function (Container $container) {
            return new DiscountCheckoutConnectorToDiscountBridge($container->getLocator()->discount()->facade());
        };

        return $container;
    }

}
