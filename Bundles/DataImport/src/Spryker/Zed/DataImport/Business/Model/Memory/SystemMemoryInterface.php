<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\Memory;

interface SystemMemoryInterface
{
    /**
     * Specification:
     * - Returns the maximum amount of memory in bytes that a script is allowed to allocate.
     *
     * @return int
     */
    public function getMemoryLimit(): int;

    /**
     * Specification:
     * - Returns current memory level usage in bytes.
     *
     * @return int
     */
    public function getCurrentMemoryUsage(): int;

    /**
     * Specification:
     * - Returns memory level usage peak in bytes.
     *
     * @return int
     */
    public function getMemoryUsagePeak(): int;
}
