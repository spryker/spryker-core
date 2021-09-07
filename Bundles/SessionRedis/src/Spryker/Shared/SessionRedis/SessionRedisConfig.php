<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class SessionRedisConfig extends AbstractSharedConfig
{
    /**
     * @var string
     */
    public const SESSION_HANDLER_REDIS = 'redis';
    /**
     * @var string
     */
    public const SESSION_HANDLER_REDIS_LOCKING = 'redis_locking';

    /**
     * @var int
     */
    protected const DEFAULT_REDIS_DATABASE = 0;

    /**
     * @api
     *
     * @return string
     */
    public function getSessionHandlerRedisName(): string
    {
        return static::SESSION_HANDLER_REDIS;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getSessionHandlerRedisLockingName(): string
    {
        return static::SESSION_HANDLER_REDIS_LOCKING;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultRedisDatabase(): string
    {
        return (string)static::DEFAULT_REDIS_DATABASE;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getLockingTimeoutMilliseconds(): int
    {
        return $this->get(SessionRedisConstants::LOCKING_TIMEOUT_MILLISECONDS, 0);
    }

    /**
     * @api
     *
     * @return int
     */
    public function getLockingRetryDelayMicroseconds(): int
    {
        return $this->get(SessionRedisConstants::LOCKING_RETRY_DELAY_MICROSECONDS, 0);
    }

    /**
     * @api
     *
     * @return int
     */
    public function getLockingLockTtlMilliseconds(): int
    {
        return $this->get(SessionRedisConstants::LOCKING_LOCK_TTL_MILLISECONDS, 0);
    }
}
