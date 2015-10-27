<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount;

use SprykerEngine\Shared\Kernel\Store;
use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class DiscountDependencyProvider extends AbstractBundleDependencyProvider
{

    const STORE_CONFIG = 'store_config';

    const FLASH_MESSENGER = 'flash_messanger';

    const PLUGIN_PROPEL_CONNECTION = 'propel_connection_plugin';

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
        $container[self::FLASH_MESSENGER] = function (Container $container) {
            return $container->getLocator()->flashMessenger()->facade();
        };
        $container[self::PLUGIN_PROPEL_CONNECTION] = function (Container $container) {
            return $container->getLocator()->propel()->pluginConnection()->get();
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
        $container[self::STORE_CONFIG] = function (Container $container) {
            return Store::getInstance();
        };

        return $container;
    }

}
