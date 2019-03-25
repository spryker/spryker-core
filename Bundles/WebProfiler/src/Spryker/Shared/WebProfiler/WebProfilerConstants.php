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
     * Specification:
     * - Enable/disable web profiler.
     *
     * @api
     */
    public const ENABLE_WEB_PROFILER = 'WEBPROFILER:ENABLE_WEB_PROFILER';

    /**
     * Specification:
     * - Path to profiler cache directory.
     *
     * @api
     */
    public const PROFILER_CACHE_DIRECTORY = 'WEBPROFILER:PROFILER_CACHE_DIRECTORY';
}
