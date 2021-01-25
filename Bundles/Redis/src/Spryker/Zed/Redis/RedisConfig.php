<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Redis;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class RedisConfig extends AbstractBundleConfig
{
    protected const PROCESS_TIMEOUT = 60;
    protected const DEFAULT_REDIS_HOST = '127.0.0.1';

    /**
     * Specification:
     * - Returns the value for the process timeout in seconds, after which an exception will be thrown.
     * - Can return int, float or null to disable timeout.
     *
     * @api
     *
     * @return int|float|null
     */
    public function getProcessTimeout()
    {
        return static::PROCESS_TIMEOUT;
    }

    /**
     * Specification:
     * - Returns default redis host.
     *
     * @api
     *
     * @return string
     */
    public function getDefaultRedisHost(): string
    {
        return static::DEFAULT_REDIS_HOST;
    }
}
