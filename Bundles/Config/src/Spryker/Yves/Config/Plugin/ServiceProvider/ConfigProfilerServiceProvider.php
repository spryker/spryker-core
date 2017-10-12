<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Config\Plugin\ServiceProvider;

use Spryker\Shared\Config\Plugin\ServiceProvider\AbstractConfigProfilerServiceProvider as SharedConfigProfilerServiceProvider;

class ConfigProfilerServiceProvider extends SharedConfigProfilerServiceProvider
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return '@Config/collector/spryker_config_profiler.html.twig';
    }

    /**
     * @return bool|string
     */
    protected function getPathToTemplates()
    {
        return realpath(dirname(__DIR__) . '/../../Theme/default');
    }
}
