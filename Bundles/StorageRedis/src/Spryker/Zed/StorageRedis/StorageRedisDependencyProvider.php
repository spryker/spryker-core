<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StorageRedis;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\StorageRedis\Dependency\Facade\StorageRedisToRedisFacadeBridge;

/**
 * @method \Spryker\Zed\StorageRedis\StorageRedisConfig getConfig()
 */
class StorageRedisDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_REDIS = 'FACADE_REDIS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addRedisFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRedisFacade(Container $container): Container
    {
        $container->set(static::FACADE_REDIS, function (Container $container) {
            return new StorageRedisToRedisFacadeBridge(
                $container->getLocator()->redis()->facade()
            );
        });

        return $container;
    }
}
