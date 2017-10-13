<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Heartbeat\Business;

interface HeartbeatFacadeInterface
{
    /**
     * @api
     *
     * @return bool
     */
    public function isSystemAlive();

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\HealthReportTransfer
     */
    public function getReport();

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\HealthIndicatorReportTransfer
     */
    public function doPropelHealthCheck();

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\HealthIndicatorReportTransfer
     */
    public function doSearchHealthCheck();

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\HealthIndicatorReportTransfer
     */
    public function doSessionHealthCheck();

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\HealthIndicatorReportTransfer
     */
    public function doStorageHealthCheck();
}
