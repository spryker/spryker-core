<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Profiler;

use Spryker\Shared\Profiler\ProfilerConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProfilerConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const MIN_NODE_EXECUTION_WALL_TIME_IN_MICRO_SEC = 10000;

    /**
     * @api
     *
     * @return bool
     */
    public function isProfilerEnabled(): bool
    {
        return $this->get(ProfilerConstants::IS_PROFILER_ENABLED, false);
    }

    /**
     * @api
     *
     * @return int
     */
    public function getMinNodeExecutionWallTimeInMicroSeconds(): int
    {
        return static::MIN_NODE_EXECUTION_WALL_TIME_IN_MICRO_SEC;
    }
}
