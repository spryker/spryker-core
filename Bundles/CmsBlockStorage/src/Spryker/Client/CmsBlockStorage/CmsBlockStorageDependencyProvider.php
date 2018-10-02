<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlockStorage;

use Spryker\Client\CmsBlockStorage\Dependency\Client\CmsBlockStorageToStorageBridge;
use Spryker\Client\CmsBlockStorage\Dependency\Service\CmsBlockStorageToSynchronizationServiceBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class CmsBlockStorageDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';
    public const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container[self::CLIENT_STORAGE] = function (Container $container) {
            return new CmsBlockStorageToStorageBridge($container->getLocator()->storage()->client());
        };

        $container[self::SERVICE_SYNCHRONIZATION] = function (Container $container) {
            return new CmsBlockStorageToSynchronizationServiceBridge($container->getLocator()->synchronization()->service());
        };

        return $container;
    }
}
