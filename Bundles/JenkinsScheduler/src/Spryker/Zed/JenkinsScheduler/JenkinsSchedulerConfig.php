<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\JenkinsScheduler;

use Spryker\Shared\JenkinsScheduler\JenkinsSchedulerConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class JenkinsSchedulerConfig extends AbstractBundleConfig
{
    protected const DEFAULT_AMOUNT_OF_DAYS_FOR_LOGFILE_ROTATION = 7;

    /**
     * @return array
     */
    public function getJenkinsConfiguration(): array
    {
        return $this->get(JenkinsSchedulerConstants::JENKINS_CONFIGURATION);
    }

    /**
     * @param string $schedulerId
     *
     * @return string
     */
    public function getJenkinsBaseUrlBySchedulerId(string $schedulerId): string
    {
        return $this->getJenkinsConfiguration()[$schedulerId];
    }

    /**
     * @return string
     */
    public function getJenkinsDirectory(): string
    {
        return $this->get(JenkinsSchedulerConstants::JENKINS_DIRECTORY);
    }

    /**
     * @return bool
     */
    public function isJenkinsCsrfProtectionEnabled(): bool
    {
        return $this->get(JenkinsSchedulerConstants::JENKINS_CSRF_PROTECTION_ENABLED, false);
    }

    /**
     * @return int
     */
    public function getAmountOfDaysForLogFileRotation(): int
    {
        return $this->get(JenkinsSchedulerConstants::JENKINS_DEFAULT_AMOUNT_OF_DAYS_FOR_LOGFILE_ROTATION, static::DEFAULT_AMOUNT_OF_DAYS_FOR_LOGFILE_ROTATION);
    }

    /**
     * @return string
     */
    public function getJenkinsJobsDirectory(): string
    {
        return $this->getJenkinsDirectory() . '/jobs';
    }

    /**
     * Returns the path to the environment configuration of cronjob functionality.
     *
     * @return string
     */
    public function getCronJobsConfigFilePath(): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            APPLICATION_ROOT_DIR,
            'config',
            'Zed',
            'cronjobs',
            'cron.conf',
        ]);
    }

    /**
     * @return string
     */
    public function getJenkinsTemplatePath(): string
    {
        return 'JenkinsScheduler/Jenkins/jenkins.xml.twig';
    }
}
