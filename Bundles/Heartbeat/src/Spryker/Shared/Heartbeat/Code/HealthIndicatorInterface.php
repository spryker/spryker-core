<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Heartbeat\Code;

interface HealthIndicatorInterface
{
    /**
     * @return \Generated\Shared\Transfer\HealthIndicatorReportTransfer
     */
    public function doHealthCheck();
}
