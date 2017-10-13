<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Availability;

use Spryker\Client\Availability\Dependency\Client\AvailabilityToLocaleBridge;
use Spryker\Client\Availability\Dependency\Client\AvailabilityToStorageBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class AvailabilityDependencyProvider extends AbstractDependencyProvider
{
    const CLIENT_LOCALE = 'client locale';
    const KV_STORAGE = 'kv storage';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container[self::KV_STORAGE] = function (Container $container) {
            return new AvailabilityToStorageBridge($container->getLocator()->storage()->client());
        };

        $container[self::CLIENT_LOCALE] = function (Container $container) {
            return new AvailabilityToLocaleBridge($container->getLocator()->locale()->client());
        };

        return $container;
    }
}
