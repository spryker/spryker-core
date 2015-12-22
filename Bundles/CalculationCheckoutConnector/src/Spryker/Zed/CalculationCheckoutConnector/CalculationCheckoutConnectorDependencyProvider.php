<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CalculationCheckoutConnector;

use Spryker\Zed\CalculationCheckoutConnector\Dependency\Facade\CalculationCheckoutConnectorToCalculationBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CalculationCheckoutConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_CALCULATION = 'calculation facade';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_CALCULATION] = function (Container $container) {
            return new CalculationCheckoutConnectorToCalculationBridge($container->getLocator()->calculation()->facade());
        };

        return $container;
    }

}
