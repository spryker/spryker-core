<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\PhpScheduleReader;

use Generated\Shared\Transfer\SchedulerScheduleTransfer;

interface PhpScheduleReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerScheduleTransfer
     */
    public function readSchedule(SchedulerScheduleTransfer $scheduleTransfer): SchedulerScheduleTransfer;
}
