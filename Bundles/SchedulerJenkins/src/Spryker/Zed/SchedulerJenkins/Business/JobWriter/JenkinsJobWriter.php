<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\JobWriter;

use Spryker\Zed\SchedulerJenkins\Business\Api\JenkinsApiInterface;
use Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig;

class JenkinsJobWriter implements JenkinsJobWriterInterface
{
    protected const CREATE_JOB_MESSAGE_TEMPLATE = '[%s] CREATE jenkins job: %s (http_response: %d)';
    protected const UPDATE_JOB_MESSAGE_TEMPLATE = '[%s] UPDATE jenkins job: %s (http_response: %d)';
    protected const DELETE_JOB_MESSAGE_TEMPLATE = '[%s] DELETE jenkins job: %s (http_response: %d)';

    protected const CREATE_JOB_URL_TEMPLATE = 'createItem?name=%s';
    protected const UPDATE_JOB_URL_TEMPLATE = 'job/%s/config.xml';
    protected const DELETE_JOB_URL_TEMPLATE = 'job/%s/doDelete';

    /**
     * @var \Spryker\Zed\SchedulerJenkins\Business\Api\JenkinsApiInterface
     */
    protected $jenkinsApi;

    /**
     * @var \Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig
     */
    protected $schedulerJenkinsConfig;

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\JenkinsApiInterface $jenkinsApi
     * @param \Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig $schedulerJenkinsConfig
     */
    public function __construct(
        JenkinsApiInterface $jenkinsApi,
        SchedulerJenkinsConfig $schedulerJenkinsConfig
    ) {
        $this->jenkinsApi = $jenkinsApi;
        $this->schedulerJenkinsConfig = $schedulerJenkinsConfig;
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
