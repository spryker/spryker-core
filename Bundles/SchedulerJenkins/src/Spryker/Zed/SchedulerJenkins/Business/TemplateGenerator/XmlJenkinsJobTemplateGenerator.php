<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\TemplateGenerator;

use Generated\Shared\Transfer\SchedulerJobTransfer;
use Spryker\Shared\Config\Environment;
use Spryker\Zed\SchedulerJenkins\Dependency\TwigEnvironment\SchedulerJenkinsToTwigEnvironmentInterface;
use Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig;

class XmlJenkinsJobTemplateGenerator implements JenkinsJobTemplateGeneratorInterface
{
    protected const LOG_ROTATE_DAYS_KEY = 'logrotate_days';

    protected const JOB_TEMPLATE_KEY = 'job';
    protected const WORKING_DIR_TEMPLATE_KEY = 'workingDir';
    protected const ENVIRONMENT_TEMPLATE_KEY = 'environment';

    /**
     * @var \Spryker\Zed\SchedulerJenkins\Dependency\TwigEnvironment\SchedulerJenkinsToTwigEnvironmentInterface
     */
    protected $twig;

    /**
     * @var \Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig
     */
    protected $schedulerJenkinsConfig;

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Dependency\TwigEnvironment\SchedulerJenkinsToTwigEnvironmentInterface $twig
     * @param \Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig $schedulerJenkinsConfig
     */
    public function __construct(
        SchedulerJenkinsToTwigEnvironmentInterface $twig,
        SchedulerJenkinsConfig $schedulerJenkinsConfig
    ) {
        $this->twig = $twig;
        $this->schedulerJenkinsConfig = $schedulerJenkinsConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerJobTransfer $jobTransfer
     *
     * @return string
     */
    public function generateJobTemplate(SchedulerJobTransfer $jobTransfer): string
    {
        $jobTransfer = $this->extendSchedulerJobTransferWithLogRotateValue($jobTransfer);

        $xmlTemplate = $this->twig->render($this->schedulerJenkinsConfig->getJenkinsTemplatePath(), [
            static::JOB_TEMPLATE_KEY => $jobTransfer->toArray(),
            static::WORKING_DIR_TEMPLATE_KEY => APPLICATION_ROOT_DIR,
            static::ENVIRONMENT_TEMPLATE_KEY => Environment::getInstance()->getEnvironment(),
        ]);

        return $xmlTemplate;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerJobTransfer $jobTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerJobTransfer
     */
    protected function extendSchedulerJobTransferWithLogRotateValue(SchedulerJobTransfer $jobTransfer): SchedulerJobTransfer
    {
        $jobPayload = $jobTransfer->getPayload();

        if (array_key_exists(static::LOG_ROTATE_DAYS_KEY, $jobPayload) && is_int($jobPayload[static::LOG_ROTATE_DAYS_KEY])) {
            return $jobTransfer;
        }

        $jobPayload[static::LOG_ROTATE_DAYS_KEY] = $this->schedulerJenkinsConfig->getAmountOfDaysForLogFileRotation();

        return $jobTransfer->setPayload($jobPayload);
    }
}
