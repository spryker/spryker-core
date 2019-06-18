<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\PhpScheduleReader\Mapper;

use Generated\Shared\Transfer\SchedulerFilterTransfer;
use Generated\Shared\Transfer\SchedulerScheduleTransfer;

interface PhpScheduleMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $filterTransfer
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     * @param array $jobs
     *
     * @return \Generated\Shared\Transfer\SchedulerScheduleTransfer
     */
    public function fromJobsArray(
        SchedulerFilterTransfer $filterTransfer,
        SchedulerScheduleTransfer $scheduleTransfer,
        array $jobs
    ): SchedulerScheduleTransfer;
}
