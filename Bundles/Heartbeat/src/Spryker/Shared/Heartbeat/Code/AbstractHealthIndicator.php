<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Heartbeat\Code;

use Generated\Shared\Transfer\HealthReportTransfer;
use Generated\Shared\Transfer\HealthDetailTransfer;
use Generated\Shared\Transfer\HealthIndicatorReportTransfer;

abstract class AbstractHealthIndicator implements HealthIndicatorInterface
{

    /**
     * @var HealthReportTransfer
     */
    private $healthIndicatorReport;

    /**
     * @return HealthIndicatorReportTransfer
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
     * @return HealthIndicatorReportTransfer
     */
    private function getHealthIndicatorReport()
    {
        if (!$this->healthIndicatorReport) {
            $this->healthIndicatorReport = new HealthIndicatorReportTransfer();
            $this->healthIndicatorReport->setName(get_class($this));
            $this->healthIndicatorReport->setStatus(true);
        }

        return $this->healthIndicatorReport;
    }

}
