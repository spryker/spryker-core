<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\Memory;

class PhpSystemMemory implements SystemMemoryInterface
{
    /**
     * @var string
     */
    protected const PHP_MEMORY_LIMIT_SETTING_KEY = 'memory_limit';

    /**
     * {@inheritDoc}
     *
     * @return int
     */
    public function getMemoryLimit(): int
    {
        $memoryLimit = (string)ini_get(static::PHP_MEMORY_LIMIT_SETTING_KEY);
        $last = strtolower($memoryLimit[strlen($memoryLimit) - 1]);

        $value = (int)substr($memoryLimit, 0, -1);
        switch ($last) {
            case 'g':
                return (int)floor($value * 1024 * 1024 * 1024);
            case 'm':
                return (int)floor($value * 1024 * 1024);
            case 'k':
                return (int)floor($value * 1024);
        }

        return (int)$value;
    }

    /**
     * {@inheritDoc}
     * - Wrapper for standart system function.
     *
     * @return int
     */
    public function getCurrentMemoryUsage(): int
    {
        return memory_get_usage(true);
    }

    /**
     * {@inheritDoc}
     * - Wrapper for standart system function.
     *
     * @return int
     */
    public function getMemoryUsagePeak(): int
    {
        return memory_get_peak_usage(true);
    }
}
