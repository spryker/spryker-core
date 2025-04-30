<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Redis\Compressor\Strategy;

class ZlibCompressorStrategy implements CompressorStrategyInterface
{
    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function isCompressed(mixed $value): bool
    {
        return str_starts_with($value, "\x1f" . "\x8b" . "\x08");
    }

    /**
     * @param string $value
     * @param int $level
     *
     * @return string|null
     */
    public function compress(string $value, int $level): ?string
    {
        if (!function_exists('gzencode')) {
            return $value;
        }

        $compressedValue = gzencode($value, $level);

        return $compressedValue ?: null;
    }

    /**
     * @param string $value
     *
     * @return string|null
     */
    public function decompress(string $value): ?string
    {
        if (!function_exists('gzdecode')) {
            return $value;
        }
        $decompressedValue = gzdecode($value);

        return $decompressedValue ?: null;
    }
}
