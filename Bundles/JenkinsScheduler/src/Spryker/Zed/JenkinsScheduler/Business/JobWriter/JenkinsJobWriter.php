<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\JenkinsScheduler\Business\JobWriter;

use Spryker\Zed\JenkinsScheduler\Business\Api\JenkinsApiInterface;
use Spryker\Zed\JenkinsScheduler\JenkinsSchedulerConfig;

class JenkinsJobWriter implements JenkinsJobWriterInterface
{
    protected const CREATE_JOB_MESSAGE_TEMPLATE = '[%s] CREATE jenkins job: %s (http_response: %d)';
    protected const UPDATE_JOB_MESSAGE_TEMPLATE = '[%s] UPDATE jenkins job: %s (http_response: %d)';
    protected const DELETE_JOB_MESSAGE_TEMPLATE = '[%s] DELETE jenkins job: %s (http_response: %d)';

    protected const CREATE_JOB_URL_TEMPLATE = 'createItem?name=%s';
    protected const UPDATE_JOB_URL_TEMPLATE = 'job/%s/config.xml';
    protected const DELETE_JOB_URL_TEMPLATE = 'job/%s/doDelete';

    /**
     * @var \Spryker\Zed\JenkinsScheduler\Business\Api\JenkinsApiInterface
     */
    protected $jenkinsApi;

    /**
     * @var \Spryker\Zed\JenkinsScheduler\JenkinsSchedulerConfig
     */
    protected $jenkinsSchedulerConfig;

    /**
     * @param \Spryker\Zed\JenkinsScheduler\Business\Api\JenkinsApiInterface $jenkinsApi
     * @param \Spryker\Zed\JenkinsScheduler\JenkinsSchedulerConfig $jenkinsSchedulerConfig
     */
    public function __construct(
        JenkinsApiInterface $jenkinsApi,
        JenkinsSchedulerConfig $jenkinsSchedulerConfig
    ) {
        $this->jenkinsApi = $jenkinsApi;
        $this->jenkinsSchedulerConfig = $jenkinsSchedulerConfig;
    }

    /**
     * @param string $schedulerId
     * @param string $name
     * @param string $jobXmlTemplate
     *
     * @return string
     */
    public function createJenkinsJob(string $schedulerId, string $name, string $jobXmlTemplate): string
    {
        $response = $this->jenkinsApi->executePostRequest(
            $schedulerId,
            sprintf(static::CREATE_JOB_URL_TEMPLATE, $name),
            $jobXmlTemplate
        );

        return $this->getResponseMessage(static::CREATE_JOB_MESSAGE_TEMPLATE, $schedulerId, $name, $response->getStatusCode());
    }

    /**
     * @param string $schedulerId
     * @param string $name
     * @param string $jobXmlTemplate
     *
     * @return string
     */
    public function updateJenkinsJob(string $schedulerId, string $name, string $jobXmlTemplate): string
    {
        $response = $this->jenkinsApi->executePostRequest(
            $schedulerId,
            sprintf(static::UPDATE_JOB_URL_TEMPLATE, $name),
            $jobXmlTemplate
        );

        return $this->getResponseMessage(static::UPDATE_JOB_MESSAGE_TEMPLATE, $schedulerId, $name, $response->getStatusCode());
    }

    /**
     * @param string $schedulerId
     * @param string $name
     *
     * @return string
     */
    public function deleteJenkinsJob(string $schedulerId, string $name): string
    {
        $response = $this->jenkinsApi->executePostRequest(
            $schedulerId,
            sprintf(static::DELETE_JOB_URL_TEMPLATE, $name)
        );

        return $this->getResponseMessage(static::DELETE_JOB_MESSAGE_TEMPLATE, $schedulerId, $name, $response->getStatusCode());
    }

    /**
     * @param string $message
     * @param string $schedulerId
     * @param string $name
     * @param int $responseCode
     *
     * @return string
     */
    protected function getResponseMessage(string $message, string $schedulerId, string $name, int $responseCode): string
    {
        return sprintf(
            $message,
            $schedulerId,
            $name,
            $responseCode
        );
    }
}
