<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\Command;

use Generated\Shared\Transfer\SchedulerFilterTransfer;
use Generated\Shared\Transfer\SchedulerResponseCollectionTransfer;
use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerScheduleTransfer;
use Spryker\Zed\Scheduler\Business\Command\Filter\SchedulerFilterInterface;
use Spryker\Zed\SchedulerExtension\Dependency\Plugin\SchedulerAdapterPluginInterface;

abstract class AbstractSchedulerCommand implements SchedulerCommandInterface
{
    /**
     * @var \Spryker\Zed\SchedulerExtension\Dependency\Plugin\ScheduleReaderPluginInterface[]
     */
    protected $schedulerReaderPlugins;

    /**
     * @var \Spryker\Zed\Scheduler\Business\Command\Filter\SchedulerFilterInterface
     */
    protected $schedulerFilter;

    /**
     * @param \Spryker\Zed\SchedulerExtension\Dependency\Plugin\ScheduleReaderPluginInterface[] $schedulerReaderPlugins
     * @param \Spryker\Zed\Scheduler\Business\Command\Filter\SchedulerFilterInterface $schedulerFilter
     */
    public function __construct(
        array $schedulerReaderPlugins,
        SchedulerFilterInterface $schedulerFilter
    ) {
        $this->schedulerReaderPlugins = $schedulerReaderPlugins;
        $this->schedulerFilter = $schedulerFilter;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    public function execute(SchedulerFilterTransfer $filterTransfer): SchedulerResponseCollectionTransfer
    {
        $responseCollectionTransfer = $this->createSchedulerResponseCollectionTransfer();
        $schedulerAdapters = $this->schedulerFilter->getFilteredSchedulerAdapters($filterTransfer);

        foreach ($schedulerAdapters as $idScheduler => $schedulerAdapterPlugin) {
            $scheduleTransfer = $this->executeScheduleReaderPlugins($idScheduler, $filterTransfer);
            $responseTransfer = $this->executeCommand($schedulerAdapterPlugin, $scheduleTransfer);

            $responseCollectionTransfer->addResponse($responseTransfer);
        }

        return $responseCollectionTransfer;
    }

    /**
     * @param \Spryker\Zed\SchedulerExtension\Dependency\Plugin\SchedulerAdapterPluginInterface $schedulerAdapterPlugin
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    abstract protected function executeCommand(
        SchedulerAdapterPluginInterface $schedulerAdapterPlugin,
        SchedulerScheduleTransfer $scheduleTransfer
    ): SchedulerResponseTransfer;

    /**
     * @param string $idScheduler
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerScheduleTransfer
     */
    protected function executeScheduleReaderPlugins(
        string $idScheduler,
        SchedulerFilterTransfer $filterTransfer
    ): SchedulerScheduleTransfer {
        $scheduleTransfer = (new SchedulerScheduleTransfer())
            ->setIdScheduler($idScheduler);

        foreach ($this->schedulerReaderPlugins as $schedulerReaderPlugin) {
            $scheduleTransfer = $schedulerReaderPlugin->readSchedule($filterTransfer, $scheduleTransfer);
        }

        return $scheduleTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    protected function createSchedulerResponseCollectionTransfer(): SchedulerResponseCollectionTransfer
    {
        return new SchedulerResponseCollectionTransfer();
    }
}
