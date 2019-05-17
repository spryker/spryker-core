<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\JenkinsScheduler\Business\TemplateGenerator;

use Spryker\Shared\Config\Environment;
use Spryker\Zed\JenkinsScheduler\Dependency\TwigEnvironment\JenkinsSchedulerToTwigEnvironmentInterface;
use Spryker\Zed\JenkinsScheduler\JenkinsSchedulerConfig;

class XmlJenkinsJobTemplateGenerator implements JenkinsJobTemplateGeneratorInterface
{
    /**
     * @var \Spryker\Zed\JenkinsScheduler\Dependency\TwigEnvironment\JenkinsSchedulerToTwigEnvironmentInterface
     */
    protected $twig;

    /**
     * @var \Spryker\Zed\JenkinsScheduler\JenkinsSchedulerConfig
     */
    protected $jenkinsSchedulerConfig;

    /**
     * @param \Spryker\Zed\JenkinsScheduler\Dependency\TwigEnvironment\JenkinsSchedulerToTwigEnvironmentInterface $twig
     * @param \Spryker\Zed\JenkinsScheduler\JenkinsSchedulerConfig $jenkinsSchedulerConfig
     */
    public function __construct(
        JenkinsSchedulerToTwigEnvironmentInterface $twig,
        JenkinsSchedulerConfig $jenkinsSchedulerConfig
    ) {
        $this->twig = $twig;
        $this->jenkinsSchedulerConfig = $jenkinsSchedulerConfig;
    }

    /**
     * @param array $job
     *
     * @return string
     */
    public function getJobTemplate(array $job): string
    {
        $job['command'] = $this->getCommand($job);
        $job['daysToKeep'] = $this->getDaysToKeep($job);
        $job['schedule'] = $this->getSchedule($job);
        $job['enable'] = $this->isEnabled($job);

        $xmlTemplate = $this->twig->render($this->jenkinsSchedulerConfig->getJenkinsTemplatePath(), [
            'job' => $job,
        ]);

        return $xmlTemplate;
    }

    /**
     * @param array $job
     *
     * @return string
     */
    protected function getCommand(array $job): string
    {
        $commandTemplate = $this->getCommandTemplate();
        $environment = Environment::getInstance();
        $customBashCommand = '';
        $destination = APPLICATION_ROOT_DIR;

        if ($environment->isNotDevelopment()) {
            $checkDeployFolderExistsBashCommand = '[ -f ' . APPLICATION_ROOT_DIR . '/deploy/vars ]';
            $sourceBashCommand = '. ' . APPLICATION_ROOT_DIR . '/deploy/vars';

            $customBashCommand = $checkDeployFolderExistsBashCommand . ' ' . '&amp;&amp;' . ' ' . $sourceBashCommand;
            $destination = '$destination_release_dir';
        }

        return sprintf(
            $commandTemplate,
            $customBashCommand,
            $environment->getEnvironment(),
            $job['store'],
            $destination,
            $this->jenkinsSchedulerConfig->getCronJobsConfigFilePath(),
            $job['command']
        );
    }

    /**
     * @return string
     */
    protected function getCommandTemplate(): string
    {
        return '%s
export APPLICATION_ENV=%s
export APPLICATION_STORE=%s
cd %s
. %s
%s';
    }

    /**
     * @param array $job
     *
     * @return int
     */
    protected function getDaysToKeep(array $job): int
    {
        if (array_key_exists('logrotate_days', $job) && is_int($job['logrotate_days'])) {
            return $job['logrotate_days'];
        }

        return $this->jenkinsSchedulerConfig->getAmountOfDaysForLogFileRotation();
    }

    /**
     * @param array $job
     *
     * @return string
     */
    protected function isEnabled(array $job): string
    {
        return $job['enable'] === true ? 'true' : 'false';
    }

    /**
     * @param array $job
     *
     * @return string
     */
    protected function getSchedule(array $job): string
    {
        $schedule = ($job['schedule'] === '') ? '' : $job['schedule'];

        if (array_key_exists('run_on_non_production', $job) && $job['run_on_non_production'] === true) {
            return $schedule;
        }

        if (Environment::isNotProduction()) {
            return '';
        }

        return $schedule;
    }
}
