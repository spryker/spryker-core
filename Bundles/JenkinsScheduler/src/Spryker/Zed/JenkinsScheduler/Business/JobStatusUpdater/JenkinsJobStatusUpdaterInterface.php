<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\JenkinsScheduler\Business\JobStatusUpdater;

use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerTransfer;

interface JenkinsJobStatusUpdaterInterface
{
    /**
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseTransfer $schedulerResponseTransfer
     * @param string $requestUrl
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function updateAllJenkinsJobsStatus(string $schedulerId, SchedulerTransfer $schedulerTransfer, SchedulerResponseTransfer $schedulerResponseTransfer, string $requestUrl): SchedulerResponseTransfer;

    /**
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseTransfer $schedulerResponseTransfer
     * @param string $requestUrl
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function updateJenkinsJobStatusByJobsName(string $schedulerId, SchedulerTransfer $schedulerTransfer, SchedulerResponseTransfer $schedulerResponseTransfer, string $requestUrl): SchedulerResponseTransfer;
}
