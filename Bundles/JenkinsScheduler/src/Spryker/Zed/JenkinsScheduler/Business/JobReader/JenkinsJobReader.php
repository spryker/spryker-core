<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\JenkinsScheduler\Business\JobReader;

use Spryker\Zed\JenkinsScheduler\Business\Api\JenkinsApiInterface;
use Spryker\Zed\JenkinsScheduler\Dependency\Service\JenkinsSchedulerToUtilEncodingServiceInterface;
use Spryker\Zed\JenkinsScheduler\JenkinsSchedulerConfig;

class JenkinsJobReader implements JenkinsJobReaderInterface
{
    protected const JENKINS_API_JOBS_URL = 'api/json/jobs?pretty=true&tree=jobs[name]';

    /**
     * @var \Spryker\Zed\JenkinsScheduler\Business\Api\JenkinsApiInterface
     */
    protected $jenkinsApi;

    /**
     * @var \Spryker\Zed\JenkinsScheduler\Dependency\Service\JenkinsSchedulerToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\JenkinsScheduler\JenkinsSchedulerConfig
     */
    protected $jenkinsSchedulerConfig;

    /**
     * @param \Spryker\Zed\JenkinsScheduler\Business\Api\JenkinsApiInterface $jenkinsApi
     * @param \Spryker\Zed\JenkinsScheduler\Dependency\Service\JenkinsSchedulerToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\JenkinsScheduler\JenkinsSchedulerConfig $jenkinsSchedulerConfig
     */
    public function __construct(
        JenkinsApiInterface $jenkinsApi,
        JenkinsSchedulerToUtilEncodingServiceInterface $utilEncodingService,
        JenkinsSchedulerConfig $jenkinsSchedulerConfig
    ) {
        $this->jenkinsApi = $jenkinsApi;
        $this->utilEncodingService = $utilEncodingService;
        $this->jenkinsSchedulerConfig = $jenkinsSchedulerConfig;
    }

    /**
     * @param string $schedulerId
     *
     * @return array
     */
    public function getExistingJobs(string $schedulerId): array
    {
        $response = $this->jenkinsApi->executeGetRequest($schedulerId, static::JENKINS_API_JOBS_URL);
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
