<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Heartbeat\Code;

use Generated\Shared\Transfer\HealthReportTransfer;

interface HealthIndicatorInterface
{

    /**
     * @param HealthReportTransfer $healthReportTransfer
     */
    public function doHealthCheck(HealthReportTransfer $healthReportTransfer);

}
