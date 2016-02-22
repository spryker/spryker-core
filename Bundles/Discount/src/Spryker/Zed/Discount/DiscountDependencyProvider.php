<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount;

use Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerBridge;
use Spryker\Zed\Propel\Communication\Plugin\Connection;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class DiscountDependencyProvider extends AbstractBundleDependencyProvider
{

    const STORE_CONFIG = 'store config';
    const FACADE_MESSENGER = 'messenger facade';
    const PLUGIN_PROPEL_CONNECTION = 'propel connection plugin';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::STORE_CONFIG] = function (Container $container) {
            return Store::getInstance();
        };

        $container[self::FACADE_MESSENGER] = function (Container $container) {
            return new DiscountToMessengerBridge($container->getLocator()->messenger()->facade());
        };

        $container[self::PLUGIN_PROPEL_CONNECTION] = function (Container $container) {
            return (new Connection())->get();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::STORE_CONFIG] = function (Container $container) {
            return Store::getInstance();
        };

        return $container;
    }

}
