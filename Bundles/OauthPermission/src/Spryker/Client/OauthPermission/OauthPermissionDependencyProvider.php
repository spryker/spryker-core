<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthPermission;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\OauthPermission\Dependency\Client\OauthPermissionToStorageRedisClientBridge;
use Spryker\Client\OauthPermission\Dependency\Service\OauthPermissionToOauthServiceBridge;
use Spryker\Client\OauthPermission\Dependency\Service\OauthPermissionToUtilEncodingServiceBridge;

/**
 * @method \Spryker\Client\OauthPermission\OauthPermissionConfig getConfig()
 */
class OauthPermissionDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const SERVICE_OAUTH = 'SERVICE_OAUTH';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @var string
     */
    public const CLIENT_STORAGE_REDIS = 'CLIENT_STORAGE_REDIS';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container = $this->addUtilEncodingService($container);
        $container = $this->addOauthService($container);
        $container = $this->addStorageRedisClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addOauthService(Container $container): Container
    {
        $container->set(static::SERVICE_OAUTH, function (Container $container) {
            return new OauthPermissionToOauthServiceBridge($container->getLocator()->oauth()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new OauthPermissionToUtilEncodingServiceBridge($container->getLocator()->utilEncoding()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStorageRedisClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORAGE_REDIS, function (Container $container) {
            return new OauthPermissionToStorageRedisClientBridge(
                $container->getLocator()->storageRedis()->client(),
            );
        });

        return $container;
    }
}
