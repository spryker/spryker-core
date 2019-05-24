<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\JobStatusUpdater;

use Generated\Shared\Transfer\SchedulerRequestTransfer;
use Generated\Shared\Transfer\SchedulerResponseCollectionTransfer;
use Spryker\Zed\SchedulerJenkins\Business\Api\JenkinsApiInterface;
use Spryker\Zed\SchedulerJenkins\Business\JobReader\JenkinsJobReaderInterface;
use Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig;

class JenkinsJobStatusUpdater implements JenkinsJobStatusUpdaterInterface
{
    protected const ALL_JOBS_MESSAGE_TEMPLATE = "Jenkins jobs have been successfully updated (response: %d)";
    protected const JOB_MESSAGE_TEMPLATE = "[%s] Jenkins job '%s' has been successfully updated (response: %d)";
    protected const JOB_NOT_FOUND_MESSAGE_TEMPLATE = "[%s] Jenkins job '%s' doesn't exist.";

    /**
     * @var \Spryker\Zed\SchedulerJenkins\Business\Api\JenkinsApiInterface
     */
    protected $jenkinsApi;

    /**
     * @var \Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig
     */
    protected $schedulerJenkinsConfig;

    /**
     * @var \Spryker\Zed\SchedulerJenkins\Business\JobReader\JenkinsJobReaderInterface
     */
    protected $jenkinsJobReader;

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\JenkinsApiInterface $jenkinsApi
     * @param \Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig $schedulerJenkinsConfig
     * @param \Spryker\Zed\SchedulerJenkins\Business\JobReader\JenkinsJobReaderInterface $jenkinsJobReader
     */
    public function __construct(
        JenkinsApiInterface $jenkinsApi,
        SchedulerJenkinsConfig $schedulerJenkinsConfig,
        JenkinsJobReaderInterface $jenkinsJobReader
    ) {
        $this->jenkinsApi = $jenkinsApi;
        $this->schedulerJenkinsConfig = $schedulerJenkinsConfig;
        $this->jenkinsJobReader = $jenkinsJobReader;
    }

    /**
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerRequestTransfer $scheduleTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer $schedulerResponseTransfer
     * @param string $requestUrl
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    public function updateAllJenkinsJobsStatus(
        string $schedulerId,
        SchedulerRequestTransfer $scheduleTransfer,
        SchedulerResponseCollectionTransfer $schedulerResponseTransfer,
        string $requestUrl
    ): SchedulerResponseCollectionTransfer {

        $jobs = array_keys($scheduleTransfer->getJobs());
        $existingJobs = $this->jenkinsJobReader->getExistingJobs($schedulerId);

        return $this->updateJenkinsJobs($schedulerId, $existingJobs, $jobs, $requestUrl, $schedulerResponseTransfer);
    }

    /**
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerRequestTransfer $scheduleTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer $schedulerResponseTransfer
     * @param string $requestUrl
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    public function updateJenkinsJobStatusByJobsName(
        string $schedulerId,
        SchedulerRequestTransfer $scheduleTransfer,
        SchedulerResponseCollectionTransfer $schedulerResponseTransfer,
        string $requestUrl
    ): SchedulerResponseCollectionTransfer {

        $jobs = $scheduleTransfer->getJobs();
        $jobsToUpdate = $scheduleTransfer->getJobNames();

        return $this->updateJenkinsJobs($schedulerId, $jobs, $jobsToUpdate, $requestUrl, $schedulerResponseTransfer);
    }

    /**
     * @param string $schedulerId
     * @param array $jobs
     * @param array $jobsToUpdate
     * @param string $requestUrl
     * @param \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    protected function updateJenkinsJobs(string $schedulerId, array $jobs, array $jobsToUpdate, string $requestUrl, SchedulerResponseCollectionTransfer $schedulerResponseTransfer): SchedulerResponseCollectionTransfer
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
