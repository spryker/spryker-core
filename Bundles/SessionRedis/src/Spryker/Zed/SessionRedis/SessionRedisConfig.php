<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionRedis;

use Generated\Shared\Transfer\RedisConfigurationTransfer;
use Spryker\Shared\SessionRedis\SessionRedisConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\SessionRedis\SessionRedisConfig getSharedConfig()
 */
class SessionRedisConfig extends AbstractBundleConfig
{
    public const SESSION_REDIS_CONNECTION_KEY = 'SESSION_ZED';

    /**
     * @return int
     */
    public function getSessionLifeTime(): int
    {
        return $this->get(SessionRedisConstants::ZED_SESSION_TIME_TO_LIVE);
    }

    /**
     * @return int
     */
    public function getLockingTimeout(): int
    {
        return $this->get(SessionRedisConstants::LOCKING_TIMEOUT_MILLISECONDS, 0);
    }

    /**
     * @return int
     */
    public function getLockingRetryDelay(): int
    {
        return $this->get(SessionRedisConstants::LOCKING_TIMEOUT_MILLISECONDS, 0);
    }

    /**
     * @return int
     */
    public function getLockingLockTtl(): int
    {
        return $this->get(SessionRedisConstants::LOCKING_LOCK_TTL_MILLISECONDS, 0);
    }

    /**
     * @return \Generated\Shared\Transfer\RedisConfigurationTransfer
     */
    public function getRedisConnectionConfiguration(): RedisConfigurationTransfer
    {
        return (new RedisConfigurationTransfer())
            ->setConnectionParameters(
                $this->getConnectionParameters()
            )
            ->setConnectionOptions(
                $this->getConnectionOptions()
            );
    }

    /**
     * @return array
     */
    public function getConnectionParameters(): array
    {
        $connectionConfiguration = $this->get(SessionRedisConstants::ZED_SESSION_PREDIS_CLIENT_CONFIGURATION, []);

        if ($connectionConfiguration) {
            return $connectionConfiguration;
        }

        return [
            $this->getSessionHandlerRedisDataSourceName(),
        ];
    }

    /**
     * @return array
     */
    public function getConnectionOptions(): array
    {
        return $this->get(SessionRedisConstants::ZED_SESSION_PREDIS_CLIENT_OPTIONS, []);
    }

    /**
     * @return string
     */
    protected function getSessionHandlerRedisDataSourceName()
    {
        return $this->getSharedConfig()->buildDataSourceName(
            $this->get(SessionRedisConstants::YVES_SESSION_REDIS_PROTOCOL),
            $this->get(SessionRedisConstants::YVES_SESSION_REDIS_HOST),
            $this->get(SessionRedisConstants::YVES_SESSION_REDIS_PORT),
            $this->get(SessionRedisConstants::YVES_SESSION_REDIS_DATABASE, $this->getSharedConfig()->getDefaultRedisDatabase()),
            $this->get(SessionRedisConstants::YVES_SESSION_REDIS_PASSWORD, false)
        );
    }
}
