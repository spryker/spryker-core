<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Processor\Configuration;

use Spryker\Shared\SchedulerJenkins\SchedulerJenkinsConfig as SharedSchedulerJenkinsConfig;
use Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface;
use Spryker\Zed\SchedulerJenkins\Business\Api\Exception\InvalidJenkinsConfiguration;
use Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig;

class ConfigurationProvider implements ConfigurationProviderInterface
{
    /**
     * @var string
     */
    protected $idScheduler;

    /**
     * @var \Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig
     */
    protected $schedulerJenkinsConfig;

    /**
     * @param string $idScheduler
     * @param \Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig $schedulerJenkinsConfig
     */
    public function __construct(string $idScheduler, SchedulerJenkinsConfig $schedulerJenkinsConfig)
    {
        $this->idScheduler = $idScheduler;
        $this->schedulerJenkinsConfig = $schedulerJenkinsConfig;
    }

    /**
     * @return string[]
     */
    public function getJenkinsAuthCredentials(): array
    {
        $schedulerJenkinsConfiguration = $this->getJenkinsConfigurationBySchedulerId($this->idScheduler);

        if (!array_key_exists(
            SharedSchedulerJenkinsConfig::SCHEDULER_JENKINS_CREDENTIALS,
            $schedulerJenkinsConfiguration
        )) {
            return [];
        }

        return $schedulerJenkinsConfiguration[SharedSchedulerJenkinsConfig::SCHEDULER_JENKINS_CREDENTIALS];
    }

    /**
     * @param string $urlPath
     *
     * @throws \Spryker\Zed\SchedulerJenkins\Business\Api\Exception\InvalidJenkinsConfiguration
     *
     * @return string
     */
    public function buildJenkinsApiUrl(string $urlPath): string
    {
        $schedulerJenkinsConfiguration = $this->getJenkinsConfigurationBySchedulerId($this->idScheduler);

        if (!array_key_exists(
            SharedSchedulerJenkinsConfig::SCHEDULER_JENKINS_BASE_URL,
            $schedulerJenkinsConfiguration
        )) {
            throw new InvalidJenkinsConfiguration('');
        }

        return rtrim($schedulerJenkinsConfiguration[SharedSchedulerJenkinsConfig::SCHEDULER_JENKINS_BASE_URL], '/') . '/' . $urlPath;
    }

    /**
     * @return bool
     */
    public function isJenkinsCsrfProtectionEnabled(): bool
    {
        $schedulerJenkinsConfiguration = $this->getJenkinsConfigurationBySchedulerId($this->idScheduler);

        if (array_key_exists(
            SharedSchedulerJenkinsConfig::SCHEDULER_JENKINS_CSRF_ENABLED,
            $schedulerJenkinsConfiguration
        )) {
            return $schedulerJenkinsConfiguration[SharedSchedulerJenkinsConfig::SCHEDULER_JENKINS_CSRF_ENABLED];
        }

        return false;
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
