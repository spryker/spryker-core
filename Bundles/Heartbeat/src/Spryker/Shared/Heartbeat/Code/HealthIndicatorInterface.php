<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Heartbeat\Code;

interface HealthIndicatorInterface
{

    /**
     * @return \Generated\Shared\Transfer\HealthIndicatorReportTransfer
     */
    public function doHealthCheck();

}
