<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerCheckoutConnector;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class CustomerCheckoutConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_CUSTOMER = 'customer facade';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_CUSTOMER] = function (Container $container) {
            return $container->getLocator()->customer()->facade();
        };

        return $container;
    }

}
