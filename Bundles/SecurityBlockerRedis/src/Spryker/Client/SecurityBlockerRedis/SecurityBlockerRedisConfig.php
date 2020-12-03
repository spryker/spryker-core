<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlockerRedis;

use Generated\Shared\Transfer\RedisConfigurationTransfer;
use Generated\Shared\Transfer\RedisCredentialsTransfer;
use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\SecurityBlockerRedis\SecurityBlockerRedisConstants;

class SecurityBlockerRedisConfig extends AbstractBundleConfig
{
    protected const REDIS_DEFAULT_DATABASE = 0;
    protected const STORAGE_REDIS_CONNECTION_KEY = 'SECURITY_BLOCKER_REDIS';

    /**
     * @api
     *
     * @return string
     */
    public function getRedisConnectionKey(): string
    {
        return static::STORAGE_REDIS_CONNECTION_KEY;
    }

    /**
     * Specification:
     * - Returns redis connection configuration used by the module.
     *
     * @api
     *
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
     * @return string[]
     */
    protected function getDataSourceNames(): array
    {
        return $this->get(SecurityBlockerRedisConstants::SECURITY_BLOCKER_REDIS_DATA_SOURCE_NAMES, []);
    }

    /**
     * @return \Generated\Shared\Transfer\RedisCredentialsTransfer
     */
    protected function getConnectionCredentials(): RedisCredentialsTransfer
    {
        return (new RedisCredentialsTransfer())
            ->setProtocol($this->get(SecurityBlockerRedisConstants::SECURITY_BLOCKER_REDIS_PROTOCOL))
            ->setHost($this->get(SecurityBlockerRedisConstants::SECURITY_BLOCKER_REDIS_HOST))
            ->setPort($this->get(SecurityBlockerRedisConstants::SECURITY_BLOCKER_REDIS_PORT))
            ->setDatabase($this->get(SecurityBlockerRedisConstants::SECURITY_BLOCKER_REDIS_DATABASE, static::REDIS_DEFAULT_DATABASE))
            ->setPassword($this->get(SecurityBlockerRedisConstants::SECURITY_BLOCKER_REDIS_PASSWORD, false))
            ->setIsPersistent($this->get(SecurityBlockerRedisConstants::SECURITY_BLOCKER_REDIS_PERSISTENT_CONNECTION, false));
    }

    /**
     * @return array
     */
    protected function getConnectionOptions(): array
    {
        return $this->get(SecurityBlockerRedisConstants::SECURITY_BLOCKER_REDIS_CONNECTION_OPTIONS, []);
    }
}
