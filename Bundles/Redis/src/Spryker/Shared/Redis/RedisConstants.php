<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Redis;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface RedisConstants
{
    /**
     * Specification:
     * - Enables/disables compressing for Redis data.
     *
     * @api
     *
     * @var string
     */
    public const REDIS_COMPRESSION_ENABLED = 'REDIS:REDIS_COMPRESSION_ENABLED';

    /**
     * Specification:
     * - Enables/disables Redis logs.
     *
     * @api
     *
     * @var string
     */
    public const REDIS_IS_DEV_MODE = 'REDIS:REDIS_IS_DEV_MODE';
}
