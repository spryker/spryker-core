<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\Command;

use Generated\Shared\Transfer\SchedulerRequestTransfer;
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
     * @var \Spryker\Zed\SchedulerExtension\Dependency\Plugin\SchedulerAdapterPluginInterface[]
     */
    protected $schedulerAdapterPlugins;

    /**
     * @var \Spryker\Zed\Scheduler\Business\Command\Filter\SchedulerFilterInterface
     */
    protected $schedulerFilter;

    /**
     * @param \Spryker\Zed\SchedulerExtension\Dependency\Plugin\ScheduleReaderPluginInterface[] $schedulerReaderPlugins
     * @param \Spryker\Zed\SchedulerExtension\Dependency\Plugin\SchedulerAdapterPluginInterface[] $schedulerAdapterPlugins
     * @param \Spryker\Zed\Scheduler\Business\Command\Filter\SchedulerFilterInterface $schedulerFilter
     */
    public function __construct(
        array $schedulerReaderPlugins,
        array $schedulerAdapterPlugins,
        SchedulerFilterInterface $schedulerFilter
    ) {
        $this->schedulerReaderPlugins = $schedulerReaderPlugins;
        $this->schedulerAdapterPlugins = $schedulerAdapterPlugins;
        $this->schedulerFilter = $schedulerFilter;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerRequestTransfer $schedulerRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    public function execute(SchedulerRequestTransfer $schedulerRequestTransfer): SchedulerResponseCollectionTransfer
    {
        $schedulerResponseCollectionTransfer = $this->createSchedulerResponseCollectionTransfer();
        $allowedSchedulerIds = $this->schedulerFilter->filterSchedulers($schedulerRequestTransfer, array_keys($this->schedulerAdapterPlugins));

        foreach ($allowedSchedulerIds as $idScheduler) {
            $schedulerAdapterPlugin = $this->getSchedulerAdapterPluginByIdScheduler($idScheduler);
            $scheduleTransfer = $this->executeScheduleReaderPlugins($idScheduler, $schedulerRequestTransfer);
            $schedulerResponseTransfer = $this->executeCommand($schedulerAdapterPlugin, $scheduleTransfer);

            $schedulerResponseCollectionTransfer
                ->addResponse($schedulerResponseTransfer);
        }

        return $schedulerResponseCollectionTransfer;
    }

    /**
     * @param string $idScheduler
     *
     * @return \Spryker\Zed\SchedulerExtension\Dependency\Plugin\SchedulerAdapterPluginInterface
     */
    protected function getSchedulerAdapterPluginByIdScheduler(string $idScheduler): SchedulerAdapterPluginInterface
    {
        return $this->schedulerAdapterPlugins[$idScheduler];
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
     * @param string $idScheduler
     * @param \Generated\Shared\Transfer\SchedulerRequestTransfer $schedulerRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerScheduleTransfer
     */
    protected function executeScheduleReaderPlugins(
        string $idScheduler,
        SchedulerRequestTransfer $schedulerRequestTransfer
    ): SchedulerScheduleTransfer {

        $scheduleTransfer = (new SchedulerScheduleTransfer())
            ->setIdScheduler($idScheduler);

        foreach ($this->schedulerReaderPlugins as $schedulerReaderPlugin) {
            $scheduleTransfer = $schedulerReaderPlugin->readSchedule($schedulerRequestTransfer, $scheduleTransfer);
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
