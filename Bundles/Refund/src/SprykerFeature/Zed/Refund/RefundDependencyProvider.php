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
    const QUERY_CONTAINER_SALES = 'QUERY_CONTAINER_SALES';

    const FACADE_SALES = 'FACADE_SALES';
    const FACADE_OMS = 'FACADE_OMS';
    const FACADE_REFUND = 'FACADE_REFUND';

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

        $container[static::FACADE_OMS] = function (Container $container) {
            return $container->getLocator()->oms()->facade();
        };

        $container[static::QUERY_CONTAINER_REFUND] = function (Container $container) {
            return $container->getLocator()->refund()->queryContainer();
        };

        $container[static::QUERY_CONTAINER_SALES] = function (Container $container) {
            return $container->getLocator()->sales()->queryContainer();
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

        $container[static::QUERY_CONTAINER_SALES] = function (Container $container) {
            return $container->getLocator()->sales()->queryContainer();
        };

        $container[static::FACADE_REFUND] = function (Container $container) {
            return $container->getLocator()->refund()->facade();
        };

        $container[self::FACADE_SALES] = function (Container $container) {
            return $container->getLocator()->sales()->facade();
        };

        $container[static::FACADE_OMS] = function (Container $container) {
            return $container->getLocator()->oms()->facade();
        };

        return $container;
    }

}
