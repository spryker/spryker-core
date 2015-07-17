<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SalesCheckoutConnector;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class SalesCheckoutConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_SALES = 'sales facade';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_SALES] = function (Container $container) {
            return $container->getLocator()->sales()->facade();
        };

        return $container;
    }

}
