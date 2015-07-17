<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Availability;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class AvailabilityDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_OMS = 'oms facade';
    const FACADE_STOCK = 'stock facade';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_OMS] = function (Container $container) {
            return $container->getLocator()->oms()->facade();
        };

        $container[self::FACADE_STOCK] = function (Container $container) {
            return $container->getLocator()->stock()->facade();
        };

        return $container;
    }

}
