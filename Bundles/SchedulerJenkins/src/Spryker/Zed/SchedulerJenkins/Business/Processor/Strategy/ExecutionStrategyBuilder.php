<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Processor\Strategy;

use Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface;
use Spryker\Zed\SchedulerJenkins\Business\Api\JenkinsApiInterface;
use Spryker\Zed\SchedulerJenkins\Business\Executor\ExecutorInterface;
use Spryker\Zed\SchedulerJenkins\Dependency\Service\SchedulerJenkinsToUtilEncodingServiceInterface;

class ExecutionStrategyBuilder implements ExecutionStrategyBuilderInterface
{
    protected const KEY_JOBS = 'jobs';
    protected const KEY_NAME = 'name';

    /**
     * @var \Spryker\Zed\SchedulerJenkins\Business\Api\JenkinsApiInterface
     */
    protected $jenkinsApi;

    /**
     * @var \Spryker\Zed\SchedulerJenkins\Dependency\Service\SchedulerJenkinsToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\SchedulerJenkins\Business\Executor\ExecutorInterface
     */
    protected $executorForExistingJob;

    /**
     * @var \Spryker\Zed\SchedulerJenkins\Business\Executor\ExecutorInterface
     */
    protected $executorForAbsentJob;

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\JenkinsApiInterface $jenkinsApi
     * @param \Spryker\Zed\SchedulerJenkins\Dependency\Service\SchedulerJenkinsToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\SchedulerJenkins\Business\Executor\ExecutorInterface $executorForExistingJob
     * @param \Spryker\Zed\SchedulerJenkins\Business\Executor\ExecutorInterface $executorForAbsentJob
     */
    public function __construct(
        JenkinsApiInterface $jenkinsApi,
        SchedulerJenkinsToUtilEncodingServiceInterface $utilEncodingService,
        ExecutorInterface $executorForExistingJob,
        ExecutorInterface $executorForAbsentJob
    ) {
        $this->jenkinsApi = $jenkinsApi;
        $this->utilEncodingService = $utilEncodingService;
        $this->executorForExistingJob = $executorForExistingJob;
        $this->executorForAbsentJob = $executorForAbsentJob;
    }

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface $configurationProvider
     *
     * @return \Spryker\Zed\SchedulerJenkins\Business\Processor\Strategy\ExecutionStrategyInterface
     */
    public function buildExecutionStrategy(ConfigurationProviderInterface $configurationProvider): ExecutionStrategyInterface
    {
        $jobs = $this->getJobs($configurationProvider);
        $executionStrategy = new ExecutionStrategy($this->executorForExistingJob, $this->executorForAbsentJob);

        if (!is_array($jobs)) {
            return $executionStrategy;
        }

        return $this->mapJobCheckerFromArray($executionStrategy, $jobs[static::KEY_JOBS] ?? []);
    }

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Processor\Strategy\ExecutionStrategy $jobChecker
     * @param array $jobs
     *
     * @return \Spryker\Zed\SchedulerJenkins\Business\Processor\Strategy\ExecutionStrategy
     */
    protected function mapJobCheckerFromArray(ExecutionStrategy $jobChecker, array $jobs): ExecutionStrategy
    {
        foreach ($jobs as $job) {
            if (is_array($job) && array_key_exists(static::KEY_NAME, $job)) {
                $jobChecker->addJobName($job[static::KEY_NAME]);
            }
        }

        return $jobChecker;
    }

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface $configurationProvider
     *
     * @return mixed|null
     */
    protected function getJobs(ConfigurationProviderInterface $configurationProvider)
    {
        $response = $this->jenkinsApi->getJobs($configurationProvider);

        if ($response->getStatus() === false) {
            return null;
        }

        $jobs = $this->utilEncodingService->decodeJson($response->getPayload(), true);

        return $jobs;
    }
}
