<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Iterator;

use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerScheduleTransfer;
use Spryker\Zed\SchedulerJenkins\Business\Iterator\Builder\SchedulerResponseBuilderInterface;
use Spryker\Zed\SchedulerJenkins\Business\Iterator\Strategy\ExecutionStrategyBuilderInterface;

class Iterator implements IteratorInterface
{
    /**
     * @var \Spryker\Zed\SchedulerJenkins\Business\Iterator\Strategy\ExecutionStrategyBuilderInterface
     */
    protected $executionStrategyBuilder;

    /**
     * @var \Spryker\Zed\SchedulerJenkins\Business\Iterator\Builder\SchedulerResponseBuilderInterface
     */
    protected $responseBuilder;

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Iterator\Strategy\ExecutionStrategyBuilderInterface $jenkinsJobReader
     * @param \Spryker\Zed\SchedulerJenkins\Business\Iterator\Builder\SchedulerResponseBuilderInterface $responseBuilder
     */
    public function __construct(
        ExecutionStrategyBuilderInterface $jenkinsJobReader,
        SchedulerResponseBuilderInterface $responseBuilder
    ) {
        $this->executionStrategyBuilder = $jenkinsJobReader;
        $this->responseBuilder = $responseBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function iterate(SchedulerScheduleTransfer $scheduleTransfer): SchedulerResponseTransfer
    {
        $idScheduler = $scheduleTransfer->getIdScheduler();
        $executionStrategy = $this->executionStrategyBuilder->buildExecutionStrategy($idScheduler);

        foreach ($scheduleTransfer->getJobs() as $jobTransfer) {
            $executor = $executionStrategy->getExecutor($jobTransfer);
            $response = $executor->execute($idScheduler, $jobTransfer);

            if ($response->getStatus() === false) {
                return $this->responseBuilder
                    ->withSchedule($scheduleTransfer)
                    ->withStatus(false)
                    ->withMessage($response->getMessage())
                    ->build();
            }
        }

        return $this->responseBuilder
            ->withSchedule($scheduleTransfer)
            ->withStatus(true)
            ->build();
    }
}
