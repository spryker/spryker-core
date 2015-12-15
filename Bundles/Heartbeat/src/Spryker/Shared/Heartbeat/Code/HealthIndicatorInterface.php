<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Heartbeat\Code;

use Generated\Shared\Transfer\HealthIndicatorReportTransfer;

interface HealthIndicatorInterface
{

    /**
     * @return HealthIndicatorReportTransfer
     */
    public function doHealthCheck();

}
