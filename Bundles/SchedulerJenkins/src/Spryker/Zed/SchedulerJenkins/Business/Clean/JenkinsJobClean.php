<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Clean;

use Generated\Shared\Transfer\SchedulerRequestTransfer;
use Generated\Shared\Transfer\SchedulerResponseCollectionTransfer;
use Spryker\Zed\SchedulerJenkins\Business\JobReader\JenkinsJobReaderInterface;
use Spryker\Zed\SchedulerJenkins\Business\JobWriter\JenkinsJobWriterInterface;

class JenkinsJobClean implements JenkinsJobCleanInterface
{
    /**
     * @var \Spryker\Zed\SchedulerJenkins\Business\JobReader\JenkinsJobReaderInterface
     */
    protected $jenkinsJobReader;

    /**
     * @var \Spryker\Zed\SchedulerJenkins\Business\JobWriter\JenkinsJobWriterInterface
     */
    protected $jenkinsJobWriter;

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\JobReader\JenkinsJobReaderInterface $jenkinsJobReader
     * @param \Spryker\Zed\SchedulerJenkins\Business\JobWriter\JenkinsJobWriterInterface $jenkinsJobWriter
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
     * @param \Generated\Shared\Transfer\SchedulerRequestTransfer $scheduleTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    public function cleanSchedulerJenkins(string $schedulerId, SchedulerRequestTransfer $scheduleTransfer, SchedulerResponseCollectionTransfer $schedulerResponseTransfer): SchedulerResponseCollectionTransfer
    {
        $jobs = $scheduleTransfer->getJobs();
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
