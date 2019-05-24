<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Resume;

use Generated\Shared\Transfer\SchedulerRequestTransfer;
use Generated\Shared\Transfer\SchedulerResponseCollectionTransfer;
use Spryker\Zed\SchedulerJenkins\Business\JobStatusUpdater\JenkinsJobStatusUpdaterInterface;

class JenkinsResume implements JenkinsResumeInterface
{
    protected const RESUME_JOB_URL_TEMPLATE = 'job/%s/enable';

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
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerRequestTransfer $scheduleTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    public function resumeSchedulerJenkins(string $schedulerId, SchedulerRequestTransfer $scheduleTransfer, SchedulerResponseCollectionTransfer $schedulerResponseTransfer): SchedulerResponseCollectionTransfer
    {
        $schedulerJobNames = $scheduleTransfer->getJobNames();

        if (!empty($schedulerJobNames)) {
            return $this->jenkinsJobStatusUpdater->updateJenkinsJobStatusByJobsName($schedulerId, $scheduleTransfer, $schedulerResponseTransfer, static::RESUME_JOB_URL_TEMPLATE);
        }

        return $this->jenkinsJobStatusUpdater->updateAllJenkinsJobsStatus($schedulerId, $scheduleTransfer, $schedulerResponseTransfer, static::RESUME_JOB_URL_TEMPLATE);
    }
}
