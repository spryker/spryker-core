<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Processor;

use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerScheduleTransfer;
use Spryker\Zed\SchedulerJenkins\Business\Processor\Builder\ConfigurationProviderBuilderInterface;
use Spryker\Zed\SchedulerJenkins\Business\Processor\Strategy\ExecutionStrategyBuilderInterface;

class ScheduleProcessor implements ScheduleProcessorInterface
{
    /**
     * @var \Spryker\Zed\SchedulerJenkins\Business\Processor\Strategy\ExecutionStrategyBuilderInterface
     */
    protected $executionStrategyBuilder;

    /**
     * @var \Spryker\Zed\SchedulerJenkins\Business\Processor\Builder\ConfigurationProviderBuilderInterface
     */
    protected $configurationProviderBuilder;

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Processor\Strategy\ExecutionStrategyBuilderInterface $executionStrategyBuilder
     * @param \Spryker\Zed\SchedulerJenkins\Business\Processor\Builder\ConfigurationProviderBuilderInterface $configurationProviderBuilder
     */
    public function __construct(
        ExecutionStrategyBuilderInterface $executionStrategyBuilder,
        ConfigurationProviderBuilderInterface $configurationProviderBuilder
    ) {
        $this->executionStrategyBuilder = $executionStrategyBuilder;
        $this->configurationProviderBuilder = $configurationProviderBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function processSchedule(SchedulerScheduleTransfer $scheduleTransfer): SchedulerResponseTransfer
    {
        $idScheduler = $scheduleTransfer->getIdScheduler();
        $configurationProvider = $this->configurationProviderBuilder->build($idScheduler);
        $executionStrategy = $this->executionStrategyBuilder->buildExecutionStrategy($configurationProvider);
        $schedulerResponseTransfer = $this->createSchedulerResponseTransfer($scheduleTransfer);

        foreach ($scheduleTransfer->getJobs() as $jobTransfer) {
            $executor = $executionStrategy->getExecutor($jobTransfer);
            $response = $executor->execute($configurationProvider, $jobTransfer);

            if ($response->getStatus() === false) {
                return $schedulerResponseTransfer
                    ->setStatus(false)
                    ->setMessage($response->getMessage());
            }
        }

        return $schedulerResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    protected function createSchedulerResponseTransfer(SchedulerScheduleTransfer $scheduleTransfer): SchedulerResponseTransfer
    {
        return (new SchedulerResponseTransfer())
            ->setSchedule($scheduleTransfer)
            ->setStatus(true);
    }
}
