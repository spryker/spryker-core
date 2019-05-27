<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\JobStatusUpdater;

use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerScheduleTransfer;

interface JenkinsJobStatusUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     * @param string $updateJobUrlTemplate
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function updateJenkinsJobStatus(
        SchedulerScheduleTransfer $scheduleTransfer,
        string $updateJobUrlTemplate
    ): SchedulerResponseTransfer;
}
