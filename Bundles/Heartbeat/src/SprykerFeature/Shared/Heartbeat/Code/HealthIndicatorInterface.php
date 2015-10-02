<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Heartbeat\Code;

use Generated\Shared\Heartbeat\HealthIndicatorReportInterface;

interface HealthIndicatorInterface
{

    /**
     * @return HealthIndicatorReportInterface
     */
    public function doHealthCheck();

}
