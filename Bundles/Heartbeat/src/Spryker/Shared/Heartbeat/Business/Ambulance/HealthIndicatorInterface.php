<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Heartbeat\Business\Ambulance;

use Generated\Shared\Transfer\HealthReportTransfer;

interface HealthIndicatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\HealthReportTransfer $healthReportTransfer
     *
     * @return void
     */
    public function doHealthCheck(HealthReportTransfer $healthReportTransfer);
}
