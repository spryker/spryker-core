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

    const FACADE_LOCALE = 'locale facade';

    const STORE_CONFIG = 'store config';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_LOCALE] = function (Container $container) {
            return $container->getLocator()->locale()->facade();
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
        $container[self::FACADE_LOCALE] = function (Container $container) {
            return $container->getLocator()->touch()->facade();
        };

        $container[self::STORE_CONFIG] = function (Container $container) {
            return Store::getInstance();
        };

        return $container;
    }

}
