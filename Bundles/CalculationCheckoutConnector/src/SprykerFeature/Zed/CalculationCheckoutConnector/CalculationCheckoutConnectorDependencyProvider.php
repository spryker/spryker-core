<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CalculationCheckoutConnector;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

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
            return $container->getLocator()->calculation()->facade();
        };

        return $container;
    }

}
