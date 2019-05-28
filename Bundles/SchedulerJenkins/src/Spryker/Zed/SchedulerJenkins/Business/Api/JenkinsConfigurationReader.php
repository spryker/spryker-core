<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Api;

use Spryker\Shared\SchedulerJenkins\SchedulerJenkinsConfig as SharedSchedulerJenkinsConfig;
use Spryker\Zed\SchedulerJenkins\Business\Api\Exception\JenkinsBaseUrlNotFound;
use Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig;

class JenkinsConfigurationReader implements JenkinsConfigurationReaderInterface
{
    /**
     * @var \Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig
     */
    protected $schedulerJenkinsConfig;

    /**
     * @param \Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig $schedulerJenkinsConfig
     */
    public function __construct(
        SchedulerJenkinsConfig $schedulerJenkinsConfig
    ) {
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

        if (!isset($schedulerJenkinsConfiguration[SharedSchedulerJenkinsConfig::SCHEDULER_JENKINS_CREDENTIALS])) {
            return [];
        }

        return $schedulerJenkinsConfiguration[SharedSchedulerJenkinsConfig::SCHEDULER_JENKINS_CREDENTIALS];
    }

    /**
     * @param string $schedulerId
     * @param string $urlPath
     *
     * @throws \Spryker\Zed\SchedulerJenkins\Business\Api\Exception\JenkinsBaseUrlNotFound
     *
     * @return string
     */
    public function getJenkinsBaseUrlBySchedulerId(string $schedulerId, string $urlPath): string
    {
        $schedulerJenkinsConfiguration = $this->getJenkinsConfigurationBySchedulerId($schedulerId);

        if (!isset($schedulerJenkinsConfiguration[SharedSchedulerJenkinsConfig::SCHEDULER_JENKINS_BASE_URL])) {
            throw new JenkinsBaseUrlNotFound();
        }

        return $schedulerJenkinsConfiguration[SharedSchedulerJenkinsConfig::SCHEDULER_JENKINS_BASE_URL] . $urlPath;
    }

    /**
     * @param string $schedulerId
     *
     * @return array
     */
    protected function getJenkinsConfigurationBySchedulerId(string $schedulerId): array
    {
        $schedulerJenkinsConfiguration = $this->schedulerJenkinsConfig->getJenkinsConfiguration();

        return $schedulerJenkinsConfiguration[$schedulerId];
    }
}
