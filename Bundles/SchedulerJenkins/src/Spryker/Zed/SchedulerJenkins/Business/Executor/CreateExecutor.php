<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Executor;

use Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer;
use Generated\Shared\Transfer\SchedulerJobTransfer;
use Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface;
use Spryker\Zed\SchedulerJenkins\Business\Api\JenkinsApiInterface;
use Spryker\Zed\SchedulerJenkins\Business\TemplateGenerator\JenkinsJobTemplateGeneratorInterface;

class CreateExecutor implements ExecutorInterface
{
    /**
     * @var \Spryker\Zed\SchedulerJenkins\Business\Api\JenkinsApiInterface
     */
    protected $jenkinsApi;

    /**
     * @var \Spryker\Zed\SchedulerJenkins\Business\TemplateGenerator\JenkinsJobTemplateGeneratorInterface
     */
    protected $jobTemplateGenerator;

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\JenkinsApiInterface $jenkinsApi
     * @param \Spryker\Zed\SchedulerJenkins\Business\TemplateGenerator\JenkinsJobTemplateGeneratorInterface $jobTemplateGenerator
     */
    public function __construct(
        JenkinsApiInterface $jenkinsApi,
        JenkinsJobTemplateGeneratorInterface $jobTemplateGenerator
    ) {
        $this->jenkinsApi = $jenkinsApi;
        $this->jobTemplateGenerator = $jobTemplateGenerator;
    }

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface $configurationProvider
     * @param \Generated\Shared\Transfer\SchedulerJobTransfer $jobTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer
     */
    public function execute(ConfigurationProviderInterface $configurationProvider, SchedulerJobTransfer $jobTransfer): SchedulerJenkinsResponseTransfer
    {
        $jobTransfer->requireName();

        $jobXmlTemplate = $this->jobTemplateGenerator->generateJobTemplate($jobTransfer);

        return $this->jenkinsApi->createJob($configurationProvider, $jobTransfer->getName(), $jobXmlTemplate);
    }
}
