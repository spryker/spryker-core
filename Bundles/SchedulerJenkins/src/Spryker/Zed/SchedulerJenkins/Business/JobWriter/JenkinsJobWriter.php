<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\JobWriter;

use Generated\Shared\Transfer\SchedulerJobResponseTransfer;
use Spryker\Zed\SchedulerJenkins\Business\Api\JenkinsApiInterface;
use Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig;

class JenkinsJobWriter implements JenkinsJobWriterInterface
{
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
     * @return \Generated\Shared\Transfer\SchedulerJobResponseTransfer
     */
    public function createJenkinsJob(string $schedulerId, string $name, string $jobXmlTemplate): SchedulerJobResponseTransfer
    {
        $response = $this->jenkinsApi->executePostRequest(
            $schedulerId,
            sprintf(static::CREATE_JOB_URL_TEMPLATE, $name),
            $jobXmlTemplate
        );

        return $this->createSchedulerJobResponseTransfer($name, $response->getStatusCode());
    }

    /**
     * @param string $schedulerId
     * @param string $name
     * @param string $jobXmlTemplate
     *
     * @return \Generated\Shared\Transfer\SchedulerJobResponseTransfer
     */
    public function updateJenkinsJob(string $schedulerId, string $name, string $jobXmlTemplate): SchedulerJobResponseTransfer
    {
        $response = $this->jenkinsApi->executePostRequest(
            $schedulerId,
            sprintf(static::UPDATE_JOB_URL_TEMPLATE, $name),
            $jobXmlTemplate
        );

        return $this->createSchedulerJobResponseTransfer($name, $response->getStatusCode());
    }

    /**
     * @param string $schedulerId
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\SchedulerJobResponseTransfer
     */
    public function deleteJenkinsJob(string $schedulerId, string $name): SchedulerJobResponseTransfer
    {
        $response = $this->jenkinsApi->executePostRequest(
            $schedulerId,
            sprintf(static::DELETE_JOB_URL_TEMPLATE, $name)
        );

        return $this->createSchedulerJobResponseTransfer($name, $response->getStatusCode());
    }

    /**
     * @param string $schedulerId
     * @param string $name
     * @param string $urlUpdateJobTemplate
     *
     * @return \Generated\Shared\Transfer\SchedulerJobResponseTransfer
     */
    public function updateJenkinsJobStatus(string $schedulerId, string $name, string $urlUpdateJobTemplate): SchedulerJobResponseTransfer
    {
        $response = $this->jenkinsApi->executePostRequest(
            $schedulerId,
            sprintf($urlUpdateJobTemplate, $name)
        );

        return $this->createSchedulerJobResponseTransfer($name, $response->getStatusCode());
    }

    /**
     * @param string $name
     * @param int $status
     *
     * @return \Generated\Shared\Transfer\SchedulerJobResponseTransfer
     */
    protected function createSchedulerJobResponseTransfer(string $name, int $status): SchedulerJobResponseTransfer
    {
        return (new SchedulerJobResponseTransfer())
            ->setName($name)
            ->setStatus($status);
    }
}
