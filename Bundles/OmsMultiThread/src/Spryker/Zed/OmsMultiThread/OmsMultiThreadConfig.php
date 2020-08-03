<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsMultiThread;

use Spryker\Shared\OmsMultiThread\OmsMultiThreadConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class OmsMultiThreadConfig extends AbstractBundleConfig
{
    protected const DEFAULT_OMS_PROCESS_WORKER_NUMBER = 1;

    /**
     * @api
     *
     * @return int
     */
    public function getNumberOfOmsProcessWorkers(): int
    {
        return $this->get(OmsMultiThreadConstants::OMS_PROCESS_WORKER_NUMBER, static::DEFAULT_OMS_PROCESS_WORKER_NUMBER);
    }
}
