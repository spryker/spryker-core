<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Redis\Compressor;

use Spryker\Client\Redis\Compressor\Strategy\CompressorStrategyInterface;
use Spryker\Client\Redis\RedisConfig;

class Compressor implements CompressorInterface
{
    /**
     * @param \Spryker\Client\Redis\RedisConfig $redisConfig
     * @param array<\Spryker\Client\Redis\Compressor\Strategy\CompressorStrategyInterface> $keyValueCompressorStrategies
     */
    public function __construct(protected RedisConfig $redisConfig, protected array $keyValueCompressorStrategies)
    {
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    public function canBeCompressed(string $value): bool
    {
        return $this->redisConfig->isCompressionEnabled()
            && $value
            && $this->keyValueCompressorStrategies
            && strlen($value) > $this->redisConfig->getMinBytesForCompression();
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    public function isCompressed(string $value): bool
    {
        if (!$value) {
            return false;
        }

        foreach ($this->keyValueCompressorStrategies as $keyValueCompressorStrategy) {
            if ($keyValueCompressorStrategy->isCompressed($value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function compress(string $value): string
    {
        $keyValueCompressorStrategy = reset($this->keyValueCompressorStrategies);
        if (!($keyValueCompressorStrategy instanceof CompressorStrategyInterface)) {
            return $value;
        }

        $compressedValue = $keyValueCompressorStrategy->compress($value, $this->redisConfig->getCompressionLevel());

        return $compressedValue ?: $value;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function decompress(string $value): string
    {
        foreach ($this->keyValueCompressorStrategies as $keyValueCompressorStrategy) {
            if ($keyValueCompressorStrategy->isCompressed($value)) {
                $decompressedValue = $keyValueCompressorStrategy->decompress($value);
                if ($decompressedValue !== null) {
                    return $decompressedValue;
                }
            }
        }

        return $value;
    }
}
