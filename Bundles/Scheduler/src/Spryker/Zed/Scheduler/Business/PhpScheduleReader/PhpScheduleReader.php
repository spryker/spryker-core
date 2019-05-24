<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\PhpScheduleReader;

use Generated\Shared\Transfer\SchedulerScheduleTransfer;
use Spryker\Zed\Scheduler\Dependency\Store\SchedulerToStoreInterface;
use Spryker\Zed\Scheduler\SchedulerConfig;

class PhpScheduleReader implements PhpScheduleReaderInterface
{
    /**
     * @var \Spryker\Zed\Scheduler\Business\PhpScheduleReader\PhpScheduleMapperInterface
     */
    protected $mapper;

    /**
     * @var \Spryker\Zed\Scheduler\Dependency\Store\SchedulerToStoreInterface
     */
    protected $store;

    /**
     * @var \Spryker\Zed\Scheduler\SchedulerConfig
     */
    protected $schedulerConfig;

    /**
     * @param \Spryker\Zed\Scheduler\Business\PhpScheduleReader\PhpScheduleMapperInterface $mapper
     * @param \Spryker\Zed\Scheduler\Dependency\Store\SchedulerToStoreInterface $store
     * @param \Spryker\Zed\Scheduler\SchedulerConfig $schedulerConfig
     */
    public function __construct(
        PhpScheduleMapperInterface $mapper,
        SchedulerToStoreInterface $store,
        SchedulerConfig $schedulerConfig
    ) {
        $this->mapper = $mapper;
        $this->store = $store;
        $this->schedulerConfig = $schedulerConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerScheduleTransfer
     */
    public function readSchedule(SchedulerScheduleTransfer $scheduleTransfer): SchedulerScheduleTransfer
    {
        $idScheduler = $scheduleTransfer->getIdScheduler();
        $sourceFileName = $this->schedulerConfig->getPhpSchedulerReaderPath($idScheduler);

        if (!file_exists($sourceFileName) || is_readable($sourceFileName)) {
            // TODO [Scheduler] warning if file is not reachable
            return $scheduleTransfer;
        }

        $jobs = [];
        include_once $sourceFileName;

        return $this->mapper->mapScheduleFromArray($scheduleTransfer, $jobs, $this->store->getStoreName());
    }
}
