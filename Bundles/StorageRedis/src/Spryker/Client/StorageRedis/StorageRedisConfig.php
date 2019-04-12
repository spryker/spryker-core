<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageRedis;

use Generated\Shared\Transfer\RedisConfigurationTransfer;
use Generated\Shared\Transfer\RedisCredentialsTransfer;
use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\StorageRedis\StorageRedisConstants;

class StorageRedisConfig extends AbstractBundleConfig
{
    protected const STORAGE_REDIS_CONNECTION_KEY = 'STORAGE_REDIS';

    protected const REDIS_DEFAULT_DATABASE = 0;

    /**
     * @return bool
     */
    public function getDebugMode(): bool
    {
        return $this->get(StorageRedisConstants::STORAGE_REDIS_DEBUG_MODE, false);
    }

    /**
     * @return \Generated\Shared\Transfer\RedisConfigurationTransfer
     */
    public function getRedisConnectionConfiguration(): RedisConfigurationTransfer
    {
        return (new RedisConfigurationTransfer())
            ->setDataSourceNames(
                $this->getDataSourceNames()
            )
            ->setConnectionCredentials(
                $this->getConnectionCredentials()
            )
            ->setClientOptions(
                $this->getConnectionOptions()
            );
    }

    /**
     * @return string
     */
    public function getRedisConnectionKey(): string
    {
        return static::STORAGE_REDIS_CONNECTION_KEY;
    }

    /**
     * @return string[]
     */
    protected function getDataSourceNames(): array
    {
        return $this->get(StorageRedisConstants::STORAGE_REDIS_DATA_SOURCE_NAMES, []);
    }

    /**
     * @return \Generated\Shared\Transfer\RedisCredentialsTransfer
     */
    protected function getConnectionCredentials(): RedisCredentialsTransfer
    {
        return (new RedisCredentialsTransfer())
            ->setProtocol($this->get(StorageRedisConstants::STORAGE_REDIS_PROTOCOL))
            ->setHost($this->get(StorageRedisConstants::STORAGE_REDIS_HOST))
            ->setPort($this->get(StorageRedisConstants::STORAGE_REDIS_PORT))
            ->setDatabase($this->get(StorageRedisConstants::STORAGE_REDIS_DATABASE, static::REDIS_DEFAULT_DATABASE))
            ->setPassword($this->get(StorageRedisConstants::STORAGE_REDIS_PASSWORD, false))
            ->setIsPersistent($this->get(StorageRedisConstants::STORAGE_REDIS_PERSISTENT_CONNECTION, false));
    }

    /**
     * @return array
     */
    protected function getConnectionOptions(): array
    {
        return $this->get(StorageRedisConstants::STORAGE_REDIS_CONNECTION_OPTIONS, []);
    }
}
