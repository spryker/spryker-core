<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Clean;

use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerScheduleTransfer;
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
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function cleanSchedulerJenkins(SchedulerScheduleTransfer $scheduleTransfer): SchedulerResponseTransfer
    {
        $idScheduler = $scheduleTransfer->getIdScheduler();
        $existingJobs = $this->jenkinsJobReader->getExistingJobs($idScheduler);

        $schedulerResponseTransfer = (new SchedulerResponseTransfer())
            ->setIdScheduler($idScheduler);

        if (empty($existingJobs)) {
            return $schedulerResponseTransfer;
        }

        foreach ($existingJobs as $job) {
            if (strpos($job, $scheduleTransfer->getStore()) !== false) {
                $schedulerJobResponseTransfer = $this->jenkinsJobWriter->deleteJenkinsJob($idScheduler, $job);
                $schedulerResponseTransfer->addSchedulerJobResponse($schedulerJobResponseTransfer);
            }
        }

        return $schedulerResponseTransfer;
    }
}
