<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\PhpScheduleReader;

use Generated\Shared\Transfer\SchedulerRequestTransfer;
use Generated\Shared\Transfer\SchedulerScheduleTransfer;

interface PhpScheduleReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\SchedulerRequestTransfer $scheduleRequestTransfer
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     *
     * @throws \Spryker\Zed\Scheduler\Business\Exception\SourceFilenameNotFoundException
     *
     * @return \Generated\Shared\Transfer\SchedulerScheduleTransfer
     */
    public function readSchedule(SchedulerRequestTransfer $scheduleRequestTransfer, SchedulerScheduleTransfer $scheduleTransfer): SchedulerScheduleTransfer;
}
