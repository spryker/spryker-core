<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Setup;

use ArrayObject;
use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerScheduleTransfer;
use Spryker\Zed\SchedulerJenkins\Business\JobReader\JenkinsJobReaderInterface;
use Spryker\Zed\SchedulerJenkins\Business\JobWriter\JenkinsJobWriterInterface;
use Spryker\Zed\SchedulerJenkins\Business\TemplateGenerator\JenkinsJobTemplateGeneratorInterface;

class JenkinsSetup implements JenkinsSetupInterface
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
     * @var \Spryker\Zed\SchedulerJenkins\Business\TemplateGenerator\JenkinsJobTemplateGeneratorInterface
     */
    protected $templateGenerator;

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\JobReader\JenkinsJobReaderInterface $jenkinsJobReader
     * @param \Spryker\Zed\SchedulerJenkins\Business\JobWriter\JenkinsJobWriterInterface $jenkinsJobWriter
     * @param \Spryker\Zed\SchedulerJenkins\Business\TemplateGenerator\JenkinsJobTemplateGeneratorInterface $templateGenerator
     */
    public function __construct(
        JenkinsJobReaderInterface $jenkinsJobReader,
        JenkinsJobWriterInterface $jenkinsJobWriter,
        JenkinsJobTemplateGeneratorInterface $templateGenerator
    ) {
        $this->jenkinsJobReader = $jenkinsJobReader;
        $this->jenkinsJobWriter = $jenkinsJobWriter;
        $this->templateGenerator = $templateGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function setup(SchedulerScheduleTransfer $scheduleTransfer): SchedulerResponseTransfer
    {
        $existingJobs = $this->jenkinsJobReader->getExistingJobs($scheduleTransfer->getIdScheduler());
        $updateOrDeleteJobsOutputMessages = $this->updateOrDeleteExistingJobs($scheduleTransfer, $existingJobs);
        $createJobsOutputMessages = $this->createJobDefinitions($scheduleTransfer, $existingJobs);

        return (new SchedulerResponseTransfer())
            ->setSchedule($scheduleTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $schedulerTransfer
     * @param array $existingJobs
     *
     * @return \Generated\Shared\Transfer\SchedulerJobResponseTransfer[]
     */
    protected function updateOrDeleteExistingJobs(SchedulerScheduleTransfer $schedulerTransfer, array $existingJobs): array
    {
        $schedulerJobResponseTransfers = [];

        if (empty($existingJobs)) {
            return $schedulerJobResponseTransfers;
        }

        foreach ($schedulerTransfer->getJobs() as $jobTransfer) {
            if (in_array($jobTransfer->getName(), $existingJobs)) {
                $xml = $this->templateGenerator->getJobTemplate($jobTransfer);
                $schedulerJobResponseTransfers[] = $this->jenkinsJobWriter->updateJenkinsJob(
                    $schedulerTransfer->getIdScheduler(),
                    $jobTransfer->getName(),
                    $xml
                );

                unset($existingJobs[array_search($jobTransfer->getName(), $existingJobs)]);
            }
        }

        $schedulerJobResponseTransfers = $this->deleteNotExistingJobs($schedulerTransfer->getIdScheduler(), $existingJobs, $schedulerJobResponseTransfers);

        return $schedulerJobResponseTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $schedulerTransfer
     * @param array $existingJobs
     *
     * @return \Generated\Shared\Transfer\SchedulerJobResponseTransfer[]
     */
    protected function createJobDefinitions(SchedulerScheduleTransfer $schedulerTransfer, array $existingJobs): array
    {
        $schedulerJobResponseTransfers = [];

        foreach ($schedulerTransfer->getJobs() as $jobTransfer) {
            if (in_array($jobTransfer->getName(), $existingJobs)) {
                continue;
            }

            $jobXmlTemplate = $this->templateGenerator->getJobTemplate($jobTransfer);
            $schedulerJobResponseTransfers[] = $this->jenkinsJobWriter->createJenkinsJob(
                $schedulerTransfer->getIdScheduler(),
                $jobTransfer->getName(),
                $jobXmlTemplate
            );
        }

        return $schedulerJobResponseTransfers;
    }

    /**
     * @param string $idScheduler
     * @param array $jobs
     * @param array $schedulerJobResponseTransfers
     *
     * @return array
     */
    protected function deleteNotExistingJobs(string $idScheduler, array $jobs, array $schedulerJobResponseTransfers): array
    {
        foreach ($jobs as $job) {
            $schedulerJobResponseTransfers[] = $this->jenkinsJobWriter->deleteJenkinsJob($idScheduler, $job);
        }

        return $schedulerJobResponseTransfers;
    }
}
