<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Lock;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Lock\Dependency\Client\LockToStorageRedisClientBridge;

/**
 * @method \Spryker\Zed\Lock\LockConfig getConfig()
 */
class LockDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_STORAGE_REDIS = 'CLIENT_STORAGE_REDIS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addStorageRedisClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addStorageRedisClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORAGE_REDIS, function (Container $container) {
            return new LockToStorageRedisClientBridge($container->getLocator()->storageRedis()->client());
        });

        return $container;
    }
}
