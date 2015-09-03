<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Refund;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class RefundDependencyProvider extends AbstractBundleDependencyProvider
{

    const QUERY_CONTAINER_REFUND = 'QUERY_CONTAINER_REFUND';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::QUERY_CONTAINER_REFUND] = function (Container $container) {
            return $container->getLocator()->refund()->queryContainer();
        };

        return $container;
    }

}
