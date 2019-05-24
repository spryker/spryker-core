<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\TemplateGenerator;

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

        $xmlTemplate = $this->twig->render($this->schedulerJenkinsConfig->getJenkinsTemplatePath(), [
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
            $this->schedulerJenkinsConfig->getCronJobsConfigFilePath(),
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

        return $this->schedulerJenkinsConfig->getAmountOfDaysForLogFileRotation();
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
