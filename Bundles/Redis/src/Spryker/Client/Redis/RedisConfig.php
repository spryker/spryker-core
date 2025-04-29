<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Redis;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\Redis\RedisConstants;

class RedisConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const MIN_BYTES_FOR_COMPRESSION = 200;

    /**
     * @var int
     */
    protected const COMPRESSION_LEVEL = 3;

    /**
     * @api
     *
     * @return bool
     */
    public function isDevelopmentMode(): bool
    {
        return $this->get(RedisConstants::REDIS_IS_DEV_MODE, false);
    }

    /**
     * Specification:
     * - These setting is used for the data compression level.
     *
     * @api
     *
     * @return int
     */
    public function getCompressionLevel(): int
    {
        return static::COMPRESSION_LEVEL;
    }

    /**
     * Specification:
     * - These setting is used for the minimum size at which data compression begins.
     *
     * @api
     *
     * @return int
     */
    public function getMinBytesForCompression(): int
    {
        return static::MIN_BYTES_FOR_COMPRESSION;
    }

    /**
     * Specification:
     * - These setting is used for enable and disable the compression.
     * - Disable only compression the decompression will work for existing data.
     *
     * @api
     *
     * @return bool
     */
    public function isCompressionEnabled(): bool
    {
        return $this->get(RedisConstants::REDIS_COMPRESSION_ENABLED, false);
    }
}
