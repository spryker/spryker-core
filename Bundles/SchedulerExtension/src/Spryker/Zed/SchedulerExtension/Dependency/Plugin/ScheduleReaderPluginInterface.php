<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerExtension\Dependency\Plugin;

use Generated\Shared\Transfer\SchedulerFilterTransfer;
use Generated\Shared\Transfer\SchedulerScheduleTransfer;

interface ScheduleReaderPluginInterface
{
    /**
     * Specification:
     * - Reads jobs definitions for particular scheduler.
     * - Performs filtration according given filter.
     * - Extends the given schedule with jobs definitions that have been read.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $schedulerFilterTransfer
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerScheduleTransfer
     */
    public function readSchedule(SchedulerFilterTransfer $schedulerFilterTransfer, SchedulerScheduleTransfer $scheduleTransfer): SchedulerScheduleTransfer;
}
