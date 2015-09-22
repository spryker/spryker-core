<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PayoneCheckoutConnector;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class PayoneCheckoutConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_PAYONE = 'payone facade';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_PAYONE] = function (Container $container) {
            return $container->getLocator()->payone()->facade();
        };

        return $container;
    }

}
