<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Console;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class ConsoleConfig extends AbstractSharedConfig
{
    /**
     * @var int
     */
    protected const MAX_REPEATABLE_EXECUTION_DURATION = 2;

    /**
     * @var int
     */
    protected const MIN_REPEATABLE_EXECUTION_DURATION = 1;

    /**
     * @api
     *
     * @return bool
     */
    public function shouldCatchExceptions(): bool
    {
        return $this->get(ConsoleConstants::CATCH_EXCEPTIONS, false);
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isDebugModeEnabled(): bool
    {
        return $this->get(ConsoleConstants::IS_DEBUG_ENABLED, false);
    }

    /**
     * Specification:
     * - Returns the maximum duration (in seconds) of repeatable command execution.
     *
     * @api
     *
     * @return int
     */
    public function getMaxRepeatableExecutionDuration(): int
    {
        return static::MAX_REPEATABLE_EXECUTION_DURATION;
    }

    /**
     * Specification:
     * - Returns the minimum duration (in seconds) of repeatable command execution.
     *
     * @api
     *
     * @return int
     */
    public function getMinRepeatableExecutionDuration(): int
    {
        return static::MIN_REPEATABLE_EXECUTION_DURATION;
    }
}
