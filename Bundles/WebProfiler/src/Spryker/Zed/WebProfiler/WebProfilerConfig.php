<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WebProfiler;

use ReflectionClass;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Symfony\Bundle\WebProfilerBundle\EventListener\WebDebugToolbarListener;

class WebProfilerConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isWebProfilerEnabled()
    {
        return $this->get(WebProfilerEnvironmentConfigConstantsZed::IS_WEB_PROFILER_ENABLED, false);
    }

    /**
     * @return string[]
     */
    public function getWebProfilerTemplatePaths(): array
    {
        $reflectionClass = new ReflectionClass(WebDebugToolbarListener::class);

        return [
            dirname(dirname((string)$reflectionClass->getFileName())) . '/Resources/views',
        ];
    }

    /**
     * @return string
     */
    public function getProfilerCacheDirectory(): string
    {
        $defaultPath = APPLICATION_ROOT_DIR . '/data/' . Store::getInstance()->getStoreName() . '/cache/profiler';

        return $this->get(WebProfilerEnvironmentConfigConstantsZed::PROFILER_CACHE_DIRECTORY, $defaultPath);
    }
}
