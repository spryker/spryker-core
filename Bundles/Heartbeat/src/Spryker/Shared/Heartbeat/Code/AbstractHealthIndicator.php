<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Heartbeat\Code;

use Generated\Shared\Transfer\HealthDetailTransfer;
use Generated\Shared\Transfer\HealthIndicatorReportTransfer;

abstract class AbstractHealthIndicator implements HealthIndicatorInterface
{
    /**
     * @var \Generated\Shared\Transfer\HealthIndicatorReportTransfer|null
     */
    private $healthIndicatorReport;

    /**
     * @return \Generated\Shared\Transfer\HealthIndicatorReportTransfer
     */
    public function doHealthCheck()
    {
        $this->healthCheck();

        return $this->getHealthIndicatorReport();
    }

    /**
     * @return void
     */
    abstract protected function healthCheck();

    /**
     * @param string $message
     *
     * @return void
     */
    protected function addDysfunction($message)
    {
        $healthIndicatorReport = $this->getHealthIndicatorReport();
        $healthIndicatorReport->setStatus(false);

        $healthDetail = new HealthDetailTransfer();
        $healthDetail->setMessage($message);

        $healthIndicatorReport->addHealthDetail($healthDetail);
    }

    /**
     * @return \Generated\Shared\Transfer\HealthIndicatorReportTransfer
     */
    private function getHealthIndicatorReport()
    {
        if (!$this->healthIndicatorReport) {
            $this->healthIndicatorReport = new HealthIndicatorReportTransfer();
            $this->healthIndicatorReport->setName(static::class);
            $this->healthIndicatorReport->setStatus(true);
        }

        return $this->healthIndicatorReport;
    }
}
