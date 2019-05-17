<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\JenkinsScheduler\Business\Setup;

use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerTransfer;
use Spryker\Zed\JenkinsScheduler\Business\JobReader\JenkinsJobReaderInterface;
use Spryker\Zed\JenkinsScheduler\Business\JobWriter\JenkinsJobWriterInterface;
use Spryker\Zed\JenkinsScheduler\Business\TemplateGenerator\JenkinsJobTemplateGeneratorInterface;

class JenkinsSchedulerSetup implements JenkinsSchedulerSetupInterface
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
     * @var \Spryker\Zed\JenkinsScheduler\Business\TemplateGenerator\JenkinsJobTemplateGeneratorInterface
     */
    protected $templateGenerator;

    /**
     * @param \Spryker\Zed\JenkinsScheduler\Business\JobReader\JenkinsJobReaderInterface $jenkinsJobReader
     * @param \Spryker\Zed\JenkinsScheduler\Business\JobWriter\JenkinsJobWriterInterface $jenkinsJobWriter
     * @param \Spryker\Zed\JenkinsScheduler\Business\TemplateGenerator\JenkinsJobTemplateGeneratorInterface $templateGenerator
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
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function setup(string $schedulerId, SchedulerTransfer $schedulerTransfer, SchedulerResponseTransfer $schedulerResponseTransfer): SchedulerResponseTransfer
    {
        $jobs = $schedulerTransfer->getJobs();
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
