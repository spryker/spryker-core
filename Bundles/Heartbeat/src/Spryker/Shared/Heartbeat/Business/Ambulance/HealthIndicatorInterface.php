<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Heartbeat\Business\Ambulance;

use Generated\Shared\Transfer\HealthReportTransfer;

interface HealthIndicatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\HealthReportTransfer $healthReportTransfer
     */
    public function doHealthCheck(HealthReportTransfer $healthReportTransfer);

}
