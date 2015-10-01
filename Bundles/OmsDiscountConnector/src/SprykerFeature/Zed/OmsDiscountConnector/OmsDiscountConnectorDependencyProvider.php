<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\OmsDiscountConnector;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class OmsDiscountConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_DISCOUNT = 'facade discount';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_DISCOUNT] = function (Container $container) {
            return $container->getLocator()->discount()->facade();
        };

        return $container;
    }
}
