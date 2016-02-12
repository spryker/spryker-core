<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\Heartbeat\Business;

interface HeartbeatFacadeInterface
{

    /**
     * @return bool
     */
    public function isSystemAlive();

    /**
     * @return \Generated\Shared\Transfer\HealthReportTransfer
     */
    public function getReport();

    /**
     * @return \Generated\Shared\Transfer\HealthIndicatorReportTransfer
     */
    public function doPropelHealthCheck();

    /**
     * @return \Generated\Shared\Transfer\HealthIndicatorReportTransfer
     */
    public function doSearchHealthCheck();

    /**
     * @return \Generated\Shared\Transfer\HealthIndicatorReportTransfer
     */
    public function doSessionHealthCheck();

    /**
     * @return \Generated\Shared\Transfer\HealthIndicatorReportTransfer
     */
    public function doStorageHealthCheck();

}
