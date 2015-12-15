<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CustomerCheckoutConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

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
