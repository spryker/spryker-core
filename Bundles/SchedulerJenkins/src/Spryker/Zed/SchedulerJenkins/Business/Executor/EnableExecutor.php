<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Executor;

use Generated\Shared\Transfer\SchedulerJobTransfer;
use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Spryker\Zed\SchedulerJenkins\Business\Api\JenkinsApiInterface;

class EnableExecutor implements ExecutorInterface
{
    protected const ENABLE_JOB_URL_TEMPLATE = 'job/%s/enable';

    /**
     * @var \Spryker\Zed\SchedulerJenkins\Business\Api\JenkinsApiInterface
     */
    protected $jenkinsApi;

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\JenkinsApiInterface $jenkinsApi
     */
    public function __construct(
        JenkinsApiInterface $jenkinsApi
    ) {
        $this->jenkinsApi = $jenkinsApi;
    }

    /**
     * @param string $idScheduler
     * @param \Generated\Shared\Transfer\SchedulerJobTransfer $schedulerJobTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function execute(string $idScheduler, SchedulerJobTransfer $schedulerJobTransfer): SchedulerResponseTransfer
    {
        $response = $this->jenkinsApi->executePostRequest(
            $idScheduler,
            sprintf(static::ENABLE_JOB_URL_TEMPLATE, $schedulerJobTransfer->getName())
        );

        return (new SchedulerResponseTransfer())
            ->setStatus($response->getStatusCode() === 200);
    }
}
