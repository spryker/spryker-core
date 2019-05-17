<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\JenkinsScheduler\Business\JobStatusUpdater;

use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerTransfer;
use Spryker\Zed\JenkinsScheduler\Business\Api\JenkinsApiInterface;
use Spryker\Zed\JenkinsScheduler\Business\JobReader\JenkinsJobReaderInterface;
use Spryker\Zed\JenkinsScheduler\JenkinsSchedulerConfig;

class JenkinsJobStatusUpdater implements JenkinsJobStatusUpdaterInterface
{
    protected const ALL_JOBS_MESSAGE_TEMPLATE = "Jenkins jobs have been successfully updated (response: %d)";
    protected const JOB_MESSAGE_TEMPLATE = "[%s] Jenkins job '%s' has been successfully updated (response: %d)";
    protected const JOB_NOT_FOUND_MESSAGE_TEMPLATE = "[%s] Jenkins job '%s' doesn't exist.";

    /**
     * @var \Spryker\Zed\JenkinsScheduler\Business\Api\JenkinsApiInterface
     */
    protected $jenkinsApi;

    /**
     * @var \Spryker\Zed\JenkinsScheduler\JenkinsSchedulerConfig
     */
    protected $jenkinsSchedulerConfig;

    /**
     * @var \Spryker\Zed\JenkinsScheduler\Business\JobReader\JenkinsJobReaderInterface
     */
    protected $jenkinsJobReader;

    /**
     * @param \Spryker\Zed\JenkinsScheduler\Business\Api\JenkinsApiInterface $jenkinsApi
     * @param \Spryker\Zed\JenkinsScheduler\JenkinsSchedulerConfig $jenkinsSchedulerConfig
     * @param \Spryker\Zed\JenkinsScheduler\Business\JobReader\JenkinsJobReaderInterface $jenkinsJobReader
     */
    public function __construct(
        JenkinsApiInterface $jenkinsApi,
        JenkinsSchedulerConfig $jenkinsSchedulerConfig,
        JenkinsJobReaderInterface $jenkinsJobReader
    ) {
        $this->jenkinsApi = $jenkinsApi;
        $this->jenkinsSchedulerConfig = $jenkinsSchedulerConfig;
        $this->jenkinsJobReader = $jenkinsJobReader;
    }

    /**
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseTransfer $schedulerResponseTransfer
     * @param string $requestUrl
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function updateAllJenkinsJobsStatus(
        string $schedulerId,
        SchedulerTransfer $schedulerTransfer,
        SchedulerResponseTransfer $schedulerResponseTransfer,
        string $requestUrl
    ): SchedulerResponseTransfer {

        $jobs = array_keys($schedulerTransfer->getJobs());
        $existingJobs = $this->jenkinsJobReader->getExistingJobs($schedulerId);

        return $this->updateJenkinsJobs($schedulerId, $existingJobs, $jobs, $requestUrl, $schedulerResponseTransfer);
    }

    /**
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseTransfer $schedulerResponseTransfer
     * @param string $requestUrl
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function updateJenkinsJobStatusByJobsName(
        string $schedulerId,
        SchedulerTransfer $schedulerTransfer,
        SchedulerResponseTransfer $schedulerResponseTransfer,
        string $requestUrl
    ): SchedulerResponseTransfer {

        $jobs = $schedulerTransfer->getJobs();
        $jobsToUpdate = $schedulerTransfer->getJobNames();

        return $this->updateJenkinsJobs($schedulerId, $jobs, $jobsToUpdate, $requestUrl, $schedulerResponseTransfer);
    }

    /**
     * @param string $schedulerId
     * @param array $jobs
     * @param array $jobsToUpdate
     * @param string $requestUrl
     * @param \Generated\Shared\Transfer\SchedulerResponseTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    protected function updateJenkinsJobs(string $schedulerId, array $jobs, array $jobsToUpdate, string $requestUrl, SchedulerResponseTransfer $schedulerResponseTransfer): SchedulerResponseTransfer
    {
        foreach ($jobsToUpdate as $key => $jobName) {
            if (!in_array($jobName, array_keys($jobs))) {
                $message = sprintf(static::JOB_NOT_FOUND_MESSAGE_TEMPLATE, $schedulerId, $jobName);
                $schedulerResponseTransfer->addMessage($message);
                continue;
            }

            $response = $this->jenkinsApi->executePostRequest($schedulerId, sprintf($requestUrl, $jobName));
            $message = sprintf(static::JOB_MESSAGE_TEMPLATE, $schedulerId, $jobName, $response->getStatusCode());
            $schedulerResponseTransfer->addMessage($message);
        }

        return $schedulerResponseTransfer;
    }
}
