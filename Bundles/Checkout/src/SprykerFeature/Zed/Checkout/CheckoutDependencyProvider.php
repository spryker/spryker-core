<?php

namespace SprykerFeature\Zed\Checkout;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class CheckoutDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_CALCULATION = 'calculation facade';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_CALCULATION] = function (Container $container) {
            return $container->getLocator()->calculation()->facade();
        };

        return $container;
    }

}
