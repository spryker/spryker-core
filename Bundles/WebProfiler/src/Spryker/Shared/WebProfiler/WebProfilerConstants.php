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
     * @deprecated Use `\SprykerShop\Yves\WebProfilerWidget\WebProfilerWidgetEnvironmentConfigConstantsYves::ENABLE_WEB_PROFILER` instead.
     * @deprecated Use `\Spryker\Zed\WebProfiler\WebProfilerWidgetEnvironmentConfigConstantsZed::ENABLE_WEB_PROFILER` instead.
     *
     * Specification:
     * - Enable/disable web profiler.
     *
     * @api
     */
    public const ENABLE_WEB_PROFILER = 'WEBPROFILER:ENABLE_WEB_PROFILER';

    /**
     * @deprecated Use `\SprykerShop\Yves\WebProfilerWidget\WebProfilerWidgetEnvironmentConfigConstantsYves::PROFILER_CACHE_DIRECTORY` instead.
     * @deprecated Use `\Spryker\Zed\WebProfiler\WebProfilerWidgetEnvironmentConfigConstantsZed::PROFILER_CACHE_DIRECTORY` instead.
     *
     * Specification:
     * - Path to profiler cache directory.
     *
     * @api
     */
    public const PROFILER_CACHE_DIRECTORY = 'WEBPROFILER:PROFILER_CACHE_DIRECTORY';
}
