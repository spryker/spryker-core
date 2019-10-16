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
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';

    public const KV_STORAGE = 'KV_STORAGE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addStorageClient($container);
        $container = $this->addLocaleClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStorageClient(Container $container)
    {
        $container[static::KV_STORAGE] = function (Container $container) {
            return new AvailabilityToStorageBridge($container->getLocator()->storage()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addLocaleClient(Container $container)
    {
        $container[static::CLIENT_LOCALE] = function (Container $container) {
            return new AvailabilityToLocaleBridge($container->getLocator()->locale()->client());
        };

        return $container;
    }
}
