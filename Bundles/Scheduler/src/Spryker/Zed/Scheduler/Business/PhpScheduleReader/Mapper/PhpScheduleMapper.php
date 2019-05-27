<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\PhpScheduleReader\Mapper;

use Generated\Shared\Transfer\SchedulerFilterTransfer;
use Generated\Shared\Transfer\SchedulerJobTransfer;
use Generated\Shared\Transfer\SchedulerScheduleTransfer;
use Spryker\Zed\Scheduler\Business\PhpScheduleReader\Filter\JobsFilterInterface;
use Spryker\Zed\Scheduler\Dependency\Store\SchedulerToStoreInterface;

class PhpScheduleMapper implements PhpScheduleMapperInterface
{
    protected const KEY_NAME = 'name';
    protected const KEY_ENABLE = 'enable';
    protected const KEY_COMMAND = 'command';
    protected const KEY_SCHEDULE = 'schedule';
    protected const KEY_STORES = 'stores';
    protected const KEY_LOG_ROTATE_DAYS = 'logrotate_days';

    /**
     * @var \Spryker\Zed\Scheduler\Business\PhpScheduleReader\Filter\JobsFilterInterface
     */
    protected $jobsFilter;

    /**
     * @var \Spryker\Zed\Scheduler\Dependency\Store\SchedulerToStoreInterface
     */
    protected $store;

    /**
     * @param \Spryker\Zed\Scheduler\Business\PhpScheduleReader\Filter\JobsFilterInterface $jobsFilter
     * @param \Spryker\Zed\Scheduler\Dependency\Store\SchedulerToStoreInterface $store
     */
    public function __construct(JobsFilterInterface $jobsFilter, SchedulerToStoreInterface $store)
    {
        $this->jobsFilter = $jobsFilter;
        $this->store = $store;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $schedulerFilterTransfer
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     * @param array $jobs
     *
     * @return \Generated\Shared\Transfer\SchedulerScheduleTransfer
     */
    public function mapScheduleFromArray(
        SchedulerFilterTransfer $schedulerFilterTransfer,
        SchedulerScheduleTransfer $scheduleTransfer,
        array $jobs
    ): SchedulerScheduleTransfer {

        $currentStoreName = $this->store->getStoreName();
        $filteredJobs = $this->jobsFilter->filterJobsByName($schedulerFilterTransfer, $jobs);

        foreach ($filteredJobs as $filteredJob) {
            $jobStores = $this->mapStoreNamesFromArray($filteredJob) ?: [$currentStoreName];

            if (in_array($currentStoreName, $jobStores, true)) {
                $scheduleTransfer
                    ->addJob($this->mapJobFromArray($filteredJob, $currentStoreName));
            }
        }

        $scheduleTransfer
            ->setStore($currentStoreName);

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
     * @param array $job
     * @param string $currentStoreName
     *
     * @return \Generated\Shared\Transfer\SchedulerJobTransfer
     */
    protected function mapJobFromArray(array $job, string $currentStoreName): SchedulerJobTransfer
    {
        return (new SchedulerJobTransfer())
            ->setName(sprintf('%s__%s', $currentStoreName, $job[static::KEY_NAME] ?? ''))
            ->setCommand($job[static::KEY_COMMAND] ?? '')
            ->setEnable($job[static::KEY_ENABLE] ?? false)
            ->setSchedule($job[static::KEY_SCHEDULE] ?? '')
            ->setStore($currentStoreName)
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
            static::KEY_LOG_ROTATE_DAYS => '',
        ]);
    }
}
