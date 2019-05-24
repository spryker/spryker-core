<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\PhpScheduleReader;

use Generated\Shared\Transfer\SchedulerJobTransfer;
use Generated\Shared\Transfer\SchedulerScheduleTransfer;

class PhpScheduleMapper implements PhpScheduleMapperInterface
{
    protected const KEY_NAME = 'name';
    protected const KEY_ENABLE = 'enable';
    protected const KEY_COMMAND = 'command';
    protected const KEY_SCHEDULE = 'schedule';
    protected const KEY_STORES = 'stores';

    /**
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     * @param array $jobs
     * @param string $currentStore
     *
     * @return \Generated\Shared\Transfer\SchedulerScheduleTransfer
     */
    public function mapScheduleFromArray(
        SchedulerScheduleTransfer $scheduleTransfer,
        array $jobs,
        string $currentStore
    ): SchedulerScheduleTransfer {

        foreach ($jobs as $job) {
            $jobStores = $this->mapStoreNamesFromArray($job) ?: [$currentStore];
            if (in_array($currentStore, $jobStores, true)) {
                $jobTransfer = new SchedulerJobTransfer();
                $scheduleTransfer->addJob($this->mapJobFromArray($jobTransfer, $job));
            }
        }

        return $scheduleTransfer;
    }

    /**
     * @param array $job
     *
     * @return array
     */
    protected function mapStoreNamesFromArray(array $job): array
    {
        return array_key_exists(static::KEY_STORES, $job) ? (array)$job[static::KEY_STORES] : [];
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerJobTransfer $jobTransfer
     * @param array $job
     *
     * @return \Generated\Shared\Transfer\SchedulerJobTransfer
     */
    protected function mapJobFromArray(SchedulerJobTransfer $jobTransfer, array $job): SchedulerJobTransfer
    {
        return $jobTransfer
            ->setName($job[static::KEY_NAME] ?? '')
            ->setCommand($job[static::KEY_COMMAND] ?? '')
            ->setEnable($job[static::KEY_ENABLE] ?? false)
            ->setSchedule($job[static::KEY_SCHEDULE] ?? '')
            ->setPayload($this->mapPayloadFromArray($job));
    }

    /**
     * @param array $job
     *
     * @return array
     */
    protected function mapPayloadFromArray(array $job): array
    {
        return array_intersect_key($job, [
            static::KEY_NAME => '',
            static::KEY_ENABLE => '',
            static::KEY_COMMAND => '',
            static::KEY_SCHEDULE => '',
            static::KEY_STORES => '',
        ]);
    }
}
