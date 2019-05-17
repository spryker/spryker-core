<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\JenkinsScheduler\Business\Resume;

use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerTransfer;
use Spryker\Zed\JenkinsScheduler\Business\JobStatusUpdater\JenkinsJobStatusUpdaterInterface;

class JenkinsResume implements JenkinsResumeInterface
{
    protected const RESUME_JOB_URL_TEMPLATE = 'job/%s/enable';

    /**
     * @var \Spryker\Zed\JenkinsScheduler\Business\JobStatusUpdater\JenkinsJobStatusUpdaterInterface
     */
    protected $jenkinsJobStatusUpdater;

    /**
     * @param \Spryker\Zed\JenkinsScheduler\Business\JobStatusUpdater\JenkinsJobStatusUpdaterInterface $jenkinsJobStatusUpdater
     */
    public function __construct(
        JenkinsJobStatusUpdaterInterface $jenkinsJobStatusUpdater
    ) {
        $this->jenkinsJobStatusUpdater = $jenkinsJobStatusUpdater;
    }

    /**
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function resumeJenkinsScheduler(string $schedulerId, SchedulerTransfer $schedulerTransfer, SchedulerResponseTransfer $schedulerResponseTransfer): SchedulerResponseTransfer
    {
        $schedulerJobNames = $schedulerTransfer->getJobNames();

        if (!empty($schedulerJobNames)) {
            return $this->jenkinsJobStatusUpdater->updateJenkinsJobStatusByJobsName($schedulerId, $schedulerTransfer, $schedulerResponseTransfer, static::RESUME_JOB_URL_TEMPLATE);
        }

        return $this->jenkinsJobStatusUpdater->updateAllJenkinsJobsStatus($schedulerId, $schedulerTransfer, $schedulerResponseTransfer, static::RESUME_JOB_URL_TEMPLATE);
    }
}
