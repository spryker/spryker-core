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

    const FACADE_SALES = 'FACADE_SALES';

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

        $container[static::QUERY_CONTAINER_REFUND] = function (Container $container) {
            return $container->getLocator()->refund()->queryContainer();
        };

        return $container;
    }

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
