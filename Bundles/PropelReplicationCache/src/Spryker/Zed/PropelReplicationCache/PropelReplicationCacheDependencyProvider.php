<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelReplicationCache;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PropelReplicationCache\Dependency\Client\PropelReplicationCacheToStorageRedisClientBridge;

/**
 * @method \Spryker\Zed\PropelReplicationCache\PropelReplicationCacheConfig getConfig()
 */
class PropelReplicationCacheDependencyProvider extends AbstractBundleDependencyProvider
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
    public function provideBusinessLayerDependencies(Container $container)
    {
        $this->addClientStorageRedis($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addClientStorageRedis(Container $container)
    {
        $container->set(static::CLIENT_STORAGE_REDIS, function (Container $container) {
            return new PropelReplicationCacheToStorageRedisClientBridge(
                $container->getLocator()->storageRedis()->client(),
            );
        });
    }
}
