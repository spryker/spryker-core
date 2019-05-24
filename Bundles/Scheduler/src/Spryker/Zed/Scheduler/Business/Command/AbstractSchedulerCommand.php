<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\Command;

use ArrayObject;
use Generated\Shared\Transfer\SchedulerRequestTransfer;
use Generated\Shared\Transfer\SchedulerResponseCollectionTransfer;
use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerScheduleTransfer;
use Spryker\Zed\Scheduler\SchedulerConfig;
use Spryker\Zed\SchedulerExtension\Dependency\Plugin\SchedulerAdapterPluginInterface;

// TODO Refactor. Probably inject iterator, that encapsulate filtration.
abstract class AbstractSchedulerCommand implements SchedulerCommandInterface
{
    /**
     * @var \Spryker\Zed\SchedulerExtension\Dependency\Plugin\ScheduleReaderPluginInterface[]
     */
    protected $schedulerReaderPlugins;

    /**
     * @var \Spryker\Zed\SchedulerExtension\Dependency\Plugin\SchedulerAdapterPluginInterface[]
     */
    protected $schedulerAdapterPlugins;

    /**
     * @var \Spryker\Zed\Scheduler\SchedulerConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\SchedulerExtension\Dependency\Plugin\ScheduleReaderPluginInterface[] $schedulerReaderPlugins
     * @param \Spryker\Zed\SchedulerExtension\Dependency\Plugin\SchedulerAdapterPluginInterface[] $schedulerAdapterPlugins
     * @param \Spryker\Zed\Scheduler\SchedulerConfig $config
     */
    public function __construct(
        array $schedulerReaderPlugins,
        array $schedulerAdapterPlugins,
        SchedulerConfig $config
    ) {
        $this->schedulerReaderPlugins = $schedulerReaderPlugins;
        $this->schedulerAdapterPlugins = $schedulerAdapterPlugins;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerRequestTransfer $schedulerRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    public function execute(SchedulerRequestTransfer $schedulerRequestTransfer): SchedulerResponseCollectionTransfer
    {
        $schedulerResponseCollection = $this->createSchedulerResponseCollection();

        $allowedSchedulerIds = $this->filterSchedulers($schedulerRequestTransfer);
        $scheduleTransfer = $this->filterJobs($schedulerRequestTransfer);

        foreach ($allowedSchedulerIds as $idScheduler) {
            $responseTransfer = $this->executeCommand($this->schedulerAdapterPlugins[$idScheduler], $scheduleTransfer);
            $schedulerResponseCollection->addResponse($responseTransfer);
        }

        return $schedulerResponseCollection;
    }

    /**
     * @param \Spryker\Zed\SchedulerExtension\Dependency\Plugin\SchedulerAdapterPluginInterface $schedulerAdapterPlugin
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $schedulerScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    abstract protected function executeCommand(
        SchedulerAdapterPluginInterface $schedulerAdapterPlugin,
        SchedulerScheduleTransfer $schedulerScheduleTransfer
    ): SchedulerResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerScheduleTransfer
     */
    protected function executeScheduleReaderPlugins(
        SchedulerScheduleTransfer $scheduleTransfer
    ): SchedulerScheduleTransfer {
        foreach ($this->schedulerReaderPlugins as $schedulerReaderPlugin) {
            $scheduleTransfer = $schedulerReaderPlugin->readSchedule($scheduleTransfer);
        }

        return $scheduleTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerRequestTransfer $schedulerRequestTransfer
     *
     * @return array
     */
    protected function filterSchedulers(SchedulerRequestTransfer $schedulerRequestTransfer): array
    {
        $allSchedulers = array_keys($this->schedulerAdapterPlugins);
        $filter = $schedulerRequestTransfer->getFilter();
        $chosenSchedulers = $filter !== null ? $filter->getSchedulers() : $allSchedulers;

        return array_intersect($chosenSchedulers, $this->config->getEnabledSchedulers(), $allSchedulers);
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerRequestTransfer $schedulerRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerScheduleTransfer
     */
    protected function filterJobs(SchedulerRequestTransfer $schedulerRequestTransfer): SchedulerScheduleTransfer
    {
        $filter = $schedulerRequestTransfer->getFilter();

        if ($filter === null) {
            return $schedulerRequestTransfer->getSchedule();
        }

        $allowedJobs = $filter->getJobs();
        $scheduleTransfer = clone $schedulerRequestTransfer->getSchedule();

        $jobs = new ArrayObject();

        foreach ($scheduleTransfer->getJobs() as $job) {
            if (in_array($job->getName(), $allowedJobs, true)) {
                $jobs[] = $job;
            }
        }

        return $scheduleTransfer->setJobs($jobs);
    }

    /**
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    protected function createSchedulerResponseCollection(): SchedulerResponseCollectionTransfer
    {
        return new SchedulerResponseCollectionTransfer();
    }
}
