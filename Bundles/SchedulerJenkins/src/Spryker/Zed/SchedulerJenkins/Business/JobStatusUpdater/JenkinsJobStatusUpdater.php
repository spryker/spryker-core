<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\JobStatusUpdater;

use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerScheduleTransfer;
use Spryker\Zed\SchedulerJenkins\Business\JobReader\JenkinsJobReaderInterface;
use Spryker\Zed\SchedulerJenkins\Business\JobWriter\JenkinsJobWriterInterface;
use Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig;

class JenkinsJobStatusUpdater implements JenkinsJobStatusUpdaterInterface
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
     * @var \Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig
     */
    protected $schedulerJenkinsConfig;

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\JobReader\JenkinsJobReaderInterface $jenkinsJobReader
     * @param \Spryker\Zed\SchedulerJenkins\Business\JobWriter\JenkinsJobWriterInterface $jenkinsJobWriter
     * @param \Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig $schedulerJenkinsConfig
     */
    public function __construct(
        JenkinsJobReaderInterface $jenkinsJobReader,
        JenkinsJobWriterInterface $jenkinsJobWriter,
        SchedulerJenkinsConfig $schedulerJenkinsConfig
    ) {
        $this->jenkinsJobReader = $jenkinsJobReader;
        $this->jenkinsJobWriter = $jenkinsJobWriter;
        $this->schedulerJenkinsConfig = $schedulerJenkinsConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     * @param string $updateJobUrlTemplate
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function updateJenkinsJobStatus(
        SchedulerScheduleTransfer $scheduleTransfer,
        string $updateJobUrlTemplate
    ): SchedulerResponseTransfer {

        $existingJobs = $this->jenkinsJobReader->getExistingJobs($scheduleTransfer->getIdScheduler());

        $schedulerResponseTransfer = (new SchedulerResponseTransfer())
            ->setSchedule($scheduleTransfer);

        if (empty($existingJobs)) {
            return $schedulerResponseTransfer;
        }

        foreach ($scheduleTransfer->getJobs() as $schedulerJobTransfer) {
            if (!in_array($schedulerJobTransfer->getName(), $existingJobs)) {
                continue;
            }
            $schedulerJobResponseMessage = $this->jenkinsJobWriter->updateJenkinsJobStatus(
                $scheduleTransfer->getIdScheduler(),
                $schedulerJobTransfer->getName(),
                $updateJobUrlTemplate
            );

            dump($schedulerJobResponseMessage);
        }

        return $schedulerResponseTransfer;
    }
}
