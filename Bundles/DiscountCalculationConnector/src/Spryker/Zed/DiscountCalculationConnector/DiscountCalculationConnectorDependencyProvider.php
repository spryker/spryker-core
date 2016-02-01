<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCalculationConnector;

use Spryker\Zed\DiscountCalculationConnector\Dependency\Facade\DiscountCalculationToDiscountBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\DiscountCalculationConnector\Dependency\Facade\DiscountCalculationToCalculationBridge;

class DiscountCalculationConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_CALCULATOR = 'calculator facade';
    const FACADE_DISCOUNT = 'discount facade';

    /**
     * @param Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_CALCULATOR] = function (Container $container) {
            return new DiscountCalculationToCalculationBridge($container->getLocator()->calculation()->facade());
        };

        return $container;
    }

    /**
     * @param Container $container
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

}
