<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Redis\Compressor\Strategy;

interface CompressorStrategyInterface
{
    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function isCompressed(mixed $value): bool;

    /**
     * @param string $value
     * @param int $level
     *
     * @return string|null
     */
    public function compress(string $value, int $level): ?string;

    /**
     * @param string $value
     *
     * @return string|null
     */
    public function decompress(string $value): ?string;
}
