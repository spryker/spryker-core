<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Profiler;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface ProfilerConstants
{
    /**
     * Specification:
     * - Enable/disable the profiler.
     *
     * @api
     *
     * @uses \Spryker\Shared\WebProfiler\WebProfilerConstants::IS_WEB_PROFILER_ENABLED
     *
     * @var string
     */
    public const IS_PROFILER_ENABLED = 'WEB_PROFILER:IS_WEB_PROFILER_ENABLED';
}
