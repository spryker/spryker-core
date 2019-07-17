<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Redis;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class RedisConfig extends AbstractBundleConfig
{
    public const DEFAULT_PROCESS_TIMEOUT = 60;

    protected const PROCESS_TIMEOUT = 60;

    /**
     * Specification:
     * - Returns the value for the process timeout in seconds, after which an exception will be thrown.
     * - Can return 0, 0.0 or null to disable timeout.
     *
     * @return int|float|null
     */
    public function getProcessTimeout()
    {
        return $this->get(static::PROCESS_TIMEOUT);
    }
}
