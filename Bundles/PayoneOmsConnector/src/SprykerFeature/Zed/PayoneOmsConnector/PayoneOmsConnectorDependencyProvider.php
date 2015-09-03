<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PayoneOmsConnector;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class PayoneOmsConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_PAYONE = 'payone facade';

    const FACADE_REFUND = 'refund facade';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::FACADE_PAYONE] = function (Container $container) {
            return $container->getLocator()->payone()->facade();
        };

        $container[static::FACADE_REFUND] = function (Container $container) {
            return $container->getLocator()->refund()->facade();
        };

        return $container;
    }

}
