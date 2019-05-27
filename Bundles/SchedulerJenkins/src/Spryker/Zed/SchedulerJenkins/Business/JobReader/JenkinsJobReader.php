<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\JobReader;

use Spryker\Zed\SchedulerJenkins\Business\Api\JenkinsApiInterface;
use Spryker\Zed\SchedulerJenkins\Dependency\Service\SchedulerJenkinsToUtilEncodingServiceInterface;
use Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig;

class JenkinsJobReader implements JenkinsJobReaderInterface
{
    protected const JENKINS_API_JOBS_URL = 'api/json/jobs?pretty=true&tree=jobs[name]';

    /**
     * @var \Spryker\Zed\SchedulerJenkins\Business\Api\JenkinsApiInterface
     */
    protected $jenkinsApi;

    /**
     * @var \Spryker\Zed\SchedulerJenkins\Dependency\Service\SchedulerJenkinsToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig
     */
    protected $schedulerJenkinsConfig;

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\JenkinsApiInterface $jenkinsApi
     * @param \Spryker\Zed\SchedulerJenkins\Dependency\Service\SchedulerJenkinsToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig $schedulerJenkinsConfig
     */
    public function __construct(
        JenkinsApiInterface $jenkinsApi,
        SchedulerJenkinsToUtilEncodingServiceInterface $utilEncodingService,
        SchedulerJenkinsConfig $schedulerJenkinsConfig
    ) {
        $this->jenkinsApi = $jenkinsApi;
        $this->utilEncodingService = $utilEncodingService;
        $this->schedulerJenkinsConfig = $schedulerJenkinsConfig;
    }

    /**
     * @param string $idScheduler
     *
     * @return array
     */
    public function getExistingJobs(string $idScheduler): array
    {
        $response = $this->jenkinsApi->executeGetRequest($idScheduler, static::JENKINS_API_JOBS_URL);
        $jobs = $this->utilEncodingService->decodeJson($response->getBody(), true);

        if (empty($jobs['jobs'])) {
            return [];
        }

        $jobsNames = [];

        foreach ($jobs['jobs'] as $job) {
            $jobsNames[] = $job['name'];
        }

        return $jobsNames;
    }
}
