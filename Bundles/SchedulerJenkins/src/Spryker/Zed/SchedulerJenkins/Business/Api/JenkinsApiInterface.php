<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Api;

use Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer;
use Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface;

interface JenkinsApiInterface
{
    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface $configurationProvider
     *
     * @return \Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer
     */
    public function getJobs(ConfigurationProviderInterface $configurationProvider): SchedulerJenkinsResponseTransfer;

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface $configurationProvider
     * @param string $jobName
     * @param string $jobXmlTemplate
     *
     * @return \Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer
     */
    public function updateJob(ConfigurationProviderInterface $configurationProvider, string $jobName, string $jobXmlTemplate): SchedulerJenkinsResponseTransfer;

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface $configurationProvider
     * @param string $jobName
     * @param string $jobXmlTemplate
     *
     * @return \Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer
     */
    public function createJob(ConfigurationProviderInterface $configurationProvider, string $jobName, string $jobXmlTemplate): SchedulerJenkinsResponseTransfer;

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface $configurationProvider
     * @param string $jobName
     *
     * @return \Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer
     */
    public function deleteJob(ConfigurationProviderInterface $configurationProvider, string $jobName): SchedulerJenkinsResponseTransfer;

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface $configurationProvider
     * @param string $jobName
     *
     * @return \Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer
     */
    public function enableJob(ConfigurationProviderInterface $configurationProvider, string $jobName): SchedulerJenkinsResponseTransfer;

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface $configurationProvider
     * @param string $jobName
     *
     * @return \Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer
     */
    public function disableJob(ConfigurationProviderInterface $configurationProvider, string $jobName): SchedulerJenkinsResponseTransfer;
}
