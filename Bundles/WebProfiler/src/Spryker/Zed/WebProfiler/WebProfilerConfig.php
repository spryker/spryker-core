<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WebProfiler;

use Spryker\Shared\WebProfiler\WebProfilerConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class WebProfilerConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isWebProfilerEnabled()
    {
        return $this->get(WebProfilerConstants::ENABLE_WEB_PROFILER, false);
    }
}
