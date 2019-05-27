<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Suspend;

use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerScheduleTransfer;
use Spryker\Zed\SchedulerJenkins\Business\JobStatusUpdater\JenkinsJobStatusUpdaterInterface;

class JenkinsSuspend implements JenkinsSuspendInterface
{
    protected const SUSPEND_JOB_URL_TEMPLATE = 'job/%s/disable';

    /**
     * @var \Spryker\Zed\SchedulerJenkins\Business\JobStatusUpdater\JenkinsJobStatusUpdaterInterface
     */
    protected $jenkinsJobStatusUpdater;

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\JobStatusUpdater\JenkinsJobStatusUpdaterInterface $jenkinsJobStatusUpdater
     */
    public function __construct(
        JenkinsJobStatusUpdaterInterface $jenkinsJobStatusUpdater
    ) {
        $this->jenkinsJobStatusUpdater = $jenkinsJobStatusUpdater;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function suspendSchedulerJenkins(SchedulerScheduleTransfer $scheduleTransfer): SchedulerResponseTransfer
    {
        return $this->jenkinsJobStatusUpdater->updateJenkinsJobStatus(
            $scheduleTransfer,
            static::SUSPEND_JOB_URL_TEMPLATE
        );
    }
}
