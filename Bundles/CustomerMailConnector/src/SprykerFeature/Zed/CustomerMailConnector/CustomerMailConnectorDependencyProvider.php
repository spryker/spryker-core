<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerMailConnector;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class CustomerMailConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_MAIL = 'mail facade';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_MAIL] = function (Container $container) {
            return $container->getLocator()->mail()->facade();
        };

        return $container;
    }

}
