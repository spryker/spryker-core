<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Heartbeat\Code;

use Generated\Shared\Transfer\HealthIndicatorReportTransfer;

interface HealthIndicatorInterface
{

    /**
     * @return \Generated\Shared\Transfer\HealthIndicatorReportTransfer
     */
    public function doHealthCheck();

}
