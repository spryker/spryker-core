<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountCalculationConnector;

use Spryker\Zed\DiscountCalculationConnector\Dependency\Facade\DiscountCalculationToDiscountBridge;
use Spryker\Zed\DiscountCalculationConnector\Dependency\Facade\DiscountCalculationToTaxBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class DiscountCalculationConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_DISCOUNT = 'discount facade';
    const FACADE_TAX = 'tax facade';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_DISCOUNT] = function (Container $container) {
            return new DiscountCalculationToDiscountBridge($container->getLocator()->discount()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_TAX] = function (Container $container) {
            return new DiscountCalculationToTaxBridge($container->getLocator()->tax()->facade());
        };

        return $container;
    }

}
