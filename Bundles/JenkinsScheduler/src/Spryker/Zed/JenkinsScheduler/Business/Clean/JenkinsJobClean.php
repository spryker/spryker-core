<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\JenkinsScheduler\Business\Clean;

use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerTransfer;
use Spryker\Zed\JenkinsScheduler\Business\JobReader\JenkinsJobReaderInterface;
use Spryker\Zed\JenkinsScheduler\Business\JobWriter\JenkinsJobWriterInterface;

class JenkinsJobClean implements JenkinsJobCleanInterface
{
    /**
     * @var \Spryker\Zed\JenkinsScheduler\Business\JobReader\JenkinsJobReaderInterface
     */
    protected $jenkinsJobReader;

    /**
     * @var \Spryker\Zed\JenkinsScheduler\Business\JobWriter\JenkinsJobWriterInterface
     */
    protected $jenkinsJobWriter;

    /**
     * @param \Spryker\Zed\JenkinsScheduler\Business\JobReader\JenkinsJobReaderInterface $jenkinsJobReader
     * @param \Spryker\Zed\JenkinsScheduler\Business\JobWriter\JenkinsJobWriterInterface $jenkinsJobWriter
     */
    public function __construct(
        JenkinsJobReaderInterface $jenkinsJobReader,
        JenkinsJobWriterInterface $jenkinsJobWriter
    ) {
        $this->jenkinsJobReader = $jenkinsJobReader;
        $this->jenkinsJobWriter = $jenkinsJobWriter;
    }

    /**
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function cleanJenkinsScheduler(string $schedulerId, SchedulerTransfer $schedulerTransfer, SchedulerResponseTransfer $schedulerResponseTransfer): SchedulerResponseTransfer
    {
        $jobs = $schedulerTransfer->getJobs();
        $existingJobs = $this->jenkinsJobReader->getExistingJobs($schedulerId);

        $jobsCleanOutputMessages = $this->cleanExistingJobs($schedulerId, $jobs, $existingJobs);

        foreach ($jobsCleanOutputMessages as $message) {
            $schedulerResponseTransfer->addMessage($message);
        }

        return $schedulerResponseTransfer;
    }

    /**
     * @param string $schedulerId
     * @param array $jobs
     * @param array $existingJobs
     *
     * @return array
     */
    protected function cleanExistingJobs(string $schedulerId, array $jobs, array $existingJobs): array
    {
        $outputMessages = [];

        if (empty($existingJobs)) {
            return $outputMessages;
        }

        foreach ($existingJobs as $name) {
            if (in_array($name, array_keys($jobs))) {
                $outputMessages[] = $this->jenkinsJobWriter->deleteJenkinsJob($schedulerId, $name);
            }
        }

        return $outputMessages;
    }
}
