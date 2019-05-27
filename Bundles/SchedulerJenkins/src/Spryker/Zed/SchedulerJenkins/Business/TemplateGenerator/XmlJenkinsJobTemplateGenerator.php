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
     * @param \Generated\Shared\Transfer\SchedulerJobTransfer $schedulerJobTransfer
     *
     * @return string
     */
    public function getJobTemplate(SchedulerJobTransfer $schedulerJobTransfer): string
    {
        $schedulerJobTransfer = $this->checkLogRotateVal($schedulerJobTransfer);

        $xmlTemplate = $this->twig->render($this->schedulerJenkinsConfig->getJenkinsTemplatePath(), [
            'job' => $schedulerJobTransfer->toArray(),
            'workingDir' => APPLICATION_ROOT_DIR,
            'environment' => Environment::getInstance()->getEnvironment(),
        ]);

        return $xmlTemplate;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerJobTransfer $schedulerJobTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerJobTransfer
     */
    protected function checkLogRotateVal(SchedulerJobTransfer $schedulerJobTransfer): SchedulerJobTransfer
    {
        $schedulerPayload = $schedulerJobTransfer->getPayload();

        if (array_key_exists('logrotate_days', $schedulerPayload) && is_int($schedulerPayload['logrotate_days'])) {
            return $schedulerJobTransfer;
        }

        $schedulerPayload['logrotate_days'] = $this->schedulerJenkinsConfig->getAmountOfDaysForLogFileRotation();

        return $schedulerJobTransfer->setPayload($schedulerPayload);
    }
}
