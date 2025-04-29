<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Redis\Compressor;

interface CompressorInterface
{
    /**
     * @param string $value
     *
     * @return bool
     */
    public function canBeCompressed(string $value): bool;

    /**
     * @param string $value
     *
     * @return bool
     */
    public function isCompressed(string $value): bool;

    /**
     * @param string $value
     *
     * @return string
     */
    public function compress(string $value): string;

    /**
     * @param string $value
     *
     * @return string
     */
    public function decompress(string $value): string;
}
