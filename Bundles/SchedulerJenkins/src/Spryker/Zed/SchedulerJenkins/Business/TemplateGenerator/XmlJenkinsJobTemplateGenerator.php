<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\TemplateGenerator;

use Generated\Shared\Transfer\SchedulerJobTransfer;
use Spryker\Zed\SchedulerJenkins\Dependency\TwigEnvironment\SchedulerJenkinsToTwigEnvironmentInterface;
use Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig;

class XmlJenkinsJobTemplateGenerator implements JenkinsJobTemplateGeneratorInterface
{
    protected const KEY_LOG_ROTATE_DAYS = 'logrotate_days';
    protected const KEY_JOB = 'job';
    protected const KEY_WORKING_DIR = 'working_dir';

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
        $jobTransfer
            ->requireRepeatPattern()
            ->requireCommand()
            ->requireStore();

        $jobTransfer = $this->extendSchedulerJobTransferWithLogRotateValue($jobTransfer);

        $xmlTemplate = $this->twig->render($this->schedulerJenkinsConfig->getJenkinsTemplateName(), [
            static::KEY_JOB => $jobTransfer->toArray(),
            static::KEY_WORKING_DIR => APPLICATION_ROOT_DIR,
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

        if (array_key_exists(static::KEY_LOG_ROTATE_DAYS, $jobPayload) && is_int($jobPayload[static::KEY_LOG_ROTATE_DAYS])) {
            return $jobTransfer;
        }

        $jobPayload[static::KEY_LOG_ROTATE_DAYS] = $this->schedulerJenkinsConfig->getAmountOfDaysForLogFileRotation();

        return $jobTransfer->setPayload($jobPayload);
    }
}
