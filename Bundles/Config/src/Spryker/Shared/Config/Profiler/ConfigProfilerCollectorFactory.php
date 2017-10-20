<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Config\Profiler;

use Spryker\Shared\Config\Config;

class ConfigProfilerCollectorFactory
{
    /**
     * @return \Spryker\Shared\Config\Profiler\ConfigProfilerCollectorInterface
     */
    public static function createConfigProfilerCollector()
    {
        return new ConfigProfilerCollector(Config::getProfileData());
    }
}
