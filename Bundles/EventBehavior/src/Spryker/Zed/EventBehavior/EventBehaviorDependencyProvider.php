<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\EventBehavior;

use Spryker\Zed\EventBehavior\Dependency\Facade\EventBehaviorToEventBridge;
use Spryker\Zed\EventBehavior\Dependency\Service\EventBehaviorToUtilEncodingBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class EventBehaviorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_EVENT = "FACADE_EVENT";
    const SERVICE_UTIL_ENCODING = 'UTIL_ENCODING_SERVICE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[static::FACADE_EVENT] = function (Container $container) {
            return new EventBehaviorToEventBridge($container->getLocator()->event()->facade());
        };

        $container[self::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new EventBehaviorToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        };

        return $container;
    }

}
