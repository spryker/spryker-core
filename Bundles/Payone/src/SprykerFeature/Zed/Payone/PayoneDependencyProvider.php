<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone;

use SprykerEngine\Shared\Kernel\Store;
use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class PayoneDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_OMS = 'oms facade';

    const FACADE_REFUND = 'refund facade';

    const STORE_CONFIG = 'store config';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_OMS] = function (Container $container) {
            return $container->getLocator()->oms()->facade();
        };

        $container[self::FACADE_REFUND] = function (Container $container) {
            return $container->getLocator()->refund()->facade();
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::STORE_CONFIG] = function (Container $container) {
            return Store::getInstance();
        };

        return $container;
    }

}
