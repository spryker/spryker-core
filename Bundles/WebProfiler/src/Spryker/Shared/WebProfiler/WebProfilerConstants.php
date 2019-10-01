<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\WebProfiler;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface WebProfilerConstants
{
    /**
     * @deprecated Use `\Spryker\Shared\WebProfiler\WebProfilerConstants::IS_WEB_PROFILER_ENABLED` instead.
     *
     * Specification:
     * - Enable/disable web profiler.
     *
     * @api
     */
    public const ENABLE_WEB_PROFILER = 'WEBPROFILER:ENABLE_WEB_PROFILER';

    /**
     * Specification:
     * - Enable/disable the web profiler.
     *
     * @api
     */
    public const IS_WEB_PROFILER_ENABLED = 'WEB_PROFILER:IS_WEB_PROFILER_ENABLED';

    /**
     * Specification:
     * - Path to the profiler cache directory.
     *
     * @api
     */
    public const PROFILER_CACHE_DIRECTORY = 'WEBPROFILER:PROFILER_CACHE_DIRECTORY';
}
