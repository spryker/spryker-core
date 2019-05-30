<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Api\Configuration;

use Spryker\Shared\SchedulerJenkins\SchedulerJenkinsConfig as SharedSchedulerJenkinsConfig;
use Spryker\Zed\SchedulerJenkins\Business\Api\Exception\WrongJenkinsConfiguration;
use Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig;

class ConfigurationProvider implements ConfigurationProviderInterface
{
    /**
     * @var \Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig
     */
    protected $schedulerJenkinsConfig;

    /**
     * @param \Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig $schedulerJenkinsConfig
     */
    public function __construct(SchedulerJenkinsConfig $schedulerJenkinsConfig)
    {
        $this->schedulerJenkinsConfig = $schedulerJenkinsConfig;
    }

    /**
     * @param string $schedulerId
     *
     * @return string[]
     */
    public function getJenkinsAuthCredentials(string $schedulerId): array
    {
        $schedulerJenkinsConfiguration = $this->getJenkinsConfigurationBySchedulerId($schedulerId);

        if (!array_key_exists(SharedSchedulerJenkinsConfig::SCHEDULER_JENKINS_CREDENTIALS,
            $schedulerJenkinsConfiguration)) {
            return [];
        }

        return $schedulerJenkinsConfiguration[SharedSchedulerJenkinsConfig::SCHEDULER_JENKINS_CREDENTIALS];
    }

    /**
     * @param string $schedulerId
     * @param string $urlPath
     *
     * @throws \Spryker\Zed\SchedulerJenkins\Business\Api\Exception\WrongJenkinsConfiguration
     *
     * @return string
     */
    public function getJenkinsBaseUrlBySchedulerId(string $schedulerId, string $urlPath): string
    {
        $schedulerJenkinsConfiguration = $this->getJenkinsConfigurationBySchedulerId($schedulerId);

        if (!array_key_exists(SharedSchedulerJenkinsConfig::SCHEDULER_JENKINS_BASE_URL,
            $schedulerJenkinsConfiguration)) {
            throw new WrongJenkinsConfiguration('');
        }

        return $schedulerJenkinsConfiguration[SharedSchedulerJenkinsConfig::SCHEDULER_JENKINS_BASE_URL] . $urlPath;
    }

    /**
     * @return bool
     */
    public function isJenkinsCsrfProtectionEnabled(): bool
    {
        return $this->schedulerJenkinsConfig->isJenkinsCsrfProtectionEnabled();
    }

    /**
     * @param string $schedulerId
     *
     * @return array
     */
    protected function getJenkinsConfigurationBySchedulerId(string $schedulerId): array
    {
        $schedulerJenkinsConfiguration = $this->schedulerJenkinsConfig->getJenkinsConfiguration();

        if (!is_array($schedulerJenkinsConfiguration)) {
            return [];
        }

        return $schedulerJenkinsConfiguration[$schedulerId];
    }
}
