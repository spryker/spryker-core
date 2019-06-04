<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Executor;

use Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer;
use Generated\Shared\Transfer\SchedulerJobTransfer;
use Spryker\Zed\SchedulerJenkins\Business\Api\Builder\JenkinsResponseBuilderInterface;

class NullExecutor implements ExecutorInterface
{
    /**
     * @var \Spryker\Zed\SchedulerJenkins\Business\Api\Builder\JenkinsResponseBuilderInterface
     */
    protected $responseBuilder;

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\Builder\JenkinsResponseBuilderInterface $responseBuilder
     */
    public function __construct(JenkinsResponseBuilderInterface $responseBuilder)
    {
        $this->responseBuilder = $responseBuilder;
    }

    /**
     * @param string $idScheduler
     * @param \Generated\Shared\Transfer\SchedulerJobTransfer $jobTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer
     */
    public function execute(string $idScheduler, SchedulerJobTransfer $jobTransfer): SchedulerJenkinsResponseTransfer
    {
        return $this->responseBuilder
            ->withStatus(true)
            ->build();
    }
}
