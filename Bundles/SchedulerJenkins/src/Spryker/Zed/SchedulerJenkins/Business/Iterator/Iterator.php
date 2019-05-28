<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Iterator;

use Generated\Shared\Transfer\SchedulerJobTransfer;
use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerScheduleTransfer;
use Spryker\Zed\SchedulerJenkins\Business\Executor\ExecutorInterface;
use Spryker\Zed\SchedulerJenkins\Business\Reader\JenkinsJobReaderInterface;

class Iterator implements IteratorInterface
{
    /**
     * @var \Spryker\Zed\SchedulerJenkins\Business\Reader\JenkinsJobReaderInterface
     */
    protected $jenkinsJobReader;

    /**
     * @var \Spryker\Zed\SchedulerJenkins\Business\Executor\ExecutorInterface
     */
    protected $executorForExistingJob;

    /**
     * @var \Spryker\Zed\SchedulerJenkins\Business\Executor\ExecutorInterface
     */
    protected $executorForAbsentJob;

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Reader\JenkinsJobReaderInterface $jenkinsJobReader
     * @param \Spryker\Zed\SchedulerJenkins\Business\Executor\ExecutorInterface $executorForExistingJob
     * @param \Spryker\Zed\SchedulerJenkins\Business\Executor\ExecutorInterface $executorForAbsentJob
     */
    public function __construct(
        JenkinsJobReaderInterface $jenkinsJobReader,
        ExecutorInterface $executorForExistingJob,
        ExecutorInterface $executorForAbsentJob
    ) {
        $this->jenkinsJobReader = $jenkinsJobReader;
        $this->executorForExistingJob = $executorForExistingJob;
        $this->executorForAbsentJob = $executorForAbsentJob;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function iterate(SchedulerScheduleTransfer $scheduleTransfer): SchedulerResponseTransfer
    {
        $idScheduler = $scheduleTransfer->getIdScheduler();
        $existingJobNames = $this->jenkinsJobReader->getExistingJobs($idScheduler);

        foreach ($scheduleTransfer->getJobs() as $jobTransfer) {
            $executor = $this->getModel($jobTransfer, $existingJobNames);
            $response = $executor->execute($idScheduler, $jobTransfer);

            if (!$response->getStatus()) {
                return $response->setSchedule($scheduleTransfer);
            }
        }

        return $this->createSchedulerResponseTransfer($scheduleTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerJobTransfer $jobTransfer
     * @param array $existingJobNames
     *
     * @return \Spryker\Zed\SchedulerJenkins\Business\Executor\ExecutorInterface
     */
    protected function getModel(SchedulerJobTransfer $jobTransfer, array $existingJobNames): ExecutorInterface
    {
        $model = in_array($jobTransfer->getName(), $existingJobNames, true)
            ? $this->executorForExistingJob
            : $this->executorForAbsentJob;

        return $model;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    protected function createSchedulerResponseTransfer(SchedulerScheduleTransfer $scheduleTransfer): SchedulerResponseTransfer
    {
        return (new SchedulerResponseTransfer())
            ->setSchedule($scheduleTransfer)
            ->setStatus(true);
    }
}
