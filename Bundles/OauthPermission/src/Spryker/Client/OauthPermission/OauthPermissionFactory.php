<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthPermission;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\OauthPermission\Dependency\Client\OauthPermissionToStorageRedisClientInterface;
use Spryker\Client\OauthPermission\Dependency\Service\OauthPermissionToOauthServiceInterface;
use Spryker\Client\OauthPermission\Dependency\Service\OauthPermissionToUtilEncodingServiceInterface;
use Spryker\Client\OauthPermission\OauthPermission\OauthPermissionReader;
use Spryker\Client\OauthPermission\OauthPermission\OauthPermissionReaderInterface;
use Spryker\Client\OauthPermission\OauthPermission\PermissionReaderInterface;
use Spryker\Client\OauthPermission\OauthPermission\StoragePermissionReader;
use Spryker\Shared\OauthPermission\KeyBuilder\OauthPermissionKeyBuilder;
use Spryker\Shared\OauthPermission\KeyBuilder\OauthPermissionKeyBuilderInterface;

class OauthPermissionFactory extends AbstractFactory
{
    /**
     * @deprecated Use `createPermissionReader()` instead.
     *
     * @return \Spryker\Client\OauthPermission\OauthPermission\OauthPermissionReaderInterface
     */
    public function createOauthPermissionReader(): OauthPermissionReaderInterface
    {
        return new OauthPermissionReader(
            $this->getOauthService(),
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @return \Spryker\Client\OauthPermission\OauthPermission\PermissionReaderInterface
     */
    public function createPermissionReader(): PermissionReaderInterface
    {
        return new StoragePermissionReader(
            $this->createKeyBuilder(),
            $this->getStorageRedisClient(),
            $this->getOauthService(),
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @return \Spryker\Shared\OauthPermission\KeyBuilder\OauthPermissionKeyBuilderInterface
     */
    public function createKeyBuilder(): OauthPermissionKeyBuilderInterface
    {
        return new OauthPermissionKeyBuilder();
    }

    /**
     * @return \Spryker\Client\OauthPermission\Dependency\Service\OauthPermissionToOauthServiceInterface
     */
    public function getOauthService(): OauthPermissionToOauthServiceInterface
    {
        return $this->getProvidedDependency(OauthPermissionDependencyProvider::SERVICE_OAUTH);
    }

    /**
     * @return \Spryker\Client\OauthPermission\Dependency\Service\OauthPermissionToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): OauthPermissionToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(OauthPermissionDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Client\OauthPermission\Dependency\Client\OauthPermissionToStorageRedisClientInterface
     */
    public function getStorageRedisClient(): OauthPermissionToStorageRedisClientInterface
    {
        return $this->getProvidedDependency(OauthPermissionDependencyProvider::CLIENT_STORAGE_REDIS);
    }
}
