<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Setup;

use Generated\Shared\Transfer\SchedulerRequestTransfer;
use Generated\Shared\Transfer\SchedulerResponseCollectionTransfer;
use Spryker\Zed\SchedulerJenkins\Business\JobReader\JenkinsJobReaderInterface;
use Spryker\Zed\SchedulerJenkins\Business\JobWriter\JenkinsJobWriterInterface;
use Spryker\Zed\SchedulerJenkins\Business\TemplateGenerator\JenkinsJobTemplateGeneratorInterface;

class SchedulerJenkinsSetup implements SchedulerJenkinsSetupInterface
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
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerRequestTransfer $scheduleTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    public function setup(string $schedulerId, SchedulerRequestTransfer $scheduleTransfer, SchedulerResponseCollectionTransfer $schedulerResponseTransfer): SchedulerResponseCollectionTransfer
    {
        $jobs = $scheduleTransfer->getJobs();
        $existingJobs = $this->jenkinsJobReader->getExistingJobs($schedulerId);

        $updateOrDeleteOutputMessages = $this->updateExistingJobs($schedulerId, $jobs, $existingJobs);
        $createOutputMessages = $this->createJobDefinitions($schedulerId, $jobs, $existingJobs);
        $setupOutputMessages = array_merge($updateOrDeleteOutputMessages, $createOutputMessages);

        foreach ($setupOutputMessages as $message) {
            $schedulerResponseTransfer->addMessage($message);
        }

        return $schedulerResponseTransfer;
    }

    /**
     * @param string $schedulerId
     * @param array $jobs
     * @param array $existingJobs
     *
     * @return string[]
     */
    protected function updateExistingJobs(string $schedulerId, array $jobs, array $existingJobs): array
    {
        $outputMessages = [];

        if (empty($existingJobs)) {
            return $outputMessages;
        }

        foreach ($existingJobs as $name) {
            if (in_array($name, array_keys($jobs))) {
                $xml = $this->templateGenerator->getJobTemplate($jobs[$name]);
                $outputMessages[] = $this->jenkinsJobWriter->updateJenkinsJob($schedulerId, $name, $xml);
            }
        }

        return $outputMessages;
    }

    /**
     * @param string $schedulerId
     * @param array $jobs
     * @param array $existingJobs
     *
     * @return string[]
     */
    protected function createJobDefinitions(string $schedulerId, array $jobs, array $existingJobs): array
    {
        $outputMessages = [];

        foreach ($jobs as $key => $job) {
            if (in_array($key, $existingJobs)) {
                continue;
            }

            $jobXmlTemplate = $this->templateGenerator->getJobTemplate($job);
            $outputMessages[] = $this->jenkinsJobWriter->createJenkinsJob($schedulerId, $key, $jobXmlTemplate);
        }

        return $outputMessages;
    }
}
