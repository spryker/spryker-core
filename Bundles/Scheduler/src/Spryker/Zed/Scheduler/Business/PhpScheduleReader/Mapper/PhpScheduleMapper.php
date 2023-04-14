<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\PhpScheduleReader\Mapper;

use Generated\Shared\Transfer\SchedulerFilterTransfer;
use Generated\Shared\Transfer\SchedulerJobTransfer;
use Generated\Shared\Transfer\SchedulerScheduleTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Scheduler\Business\PhpScheduleReader\Filter\JobsFilterInterface;
use Spryker\Zed\Scheduler\SchedulerConfig;

class PhpScheduleMapper implements PhpScheduleMapperInterface
{
    /**
     * @var string
     */
    protected const KEY_NAME = 'name';

    /**
     * @var string
     */
    protected const KEY_ENABLE = 'enable';

    /**
     * @var string
     */
    protected const KEY_COMMAND = 'command';

    /**
     * @var string
     */
    protected const KEY_SCHEDULE = 'schedule';

    /**
     * @var string
     */
    protected const KEY_STORES = 'stores';

    /**
     * @var string
     */
    protected const KEY_ROLE = 'role';

    /**
     * @var \Spryker\Zed\Scheduler\Business\PhpScheduleReader\Filter\JobsFilterInterface
     */
    protected $jobsFilter;

    /**
     * @var \Spryker\Zed\Scheduler\SchedulerConfig
     */
    protected SchedulerConfig $schedulerConfig;

    /**
     * @param \Spryker\Zed\Scheduler\Business\PhpScheduleReader\Filter\JobsFilterInterface $jobsFilter
     * @param \Spryker\Zed\Scheduler\SchedulerConfig $schedulerConfig
     */
    public function __construct(JobsFilterInterface $jobsFilter, SchedulerConfig $schedulerConfig)
    {
        $this->jobsFilter = $jobsFilter;
        $this->schedulerConfig = $schedulerConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $filterTransfer
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     * @param array $jobs
     *
     * @return \Generated\Shared\Transfer\SchedulerScheduleTransfer
     */
    public function fromJobsArray(
        SchedulerFilterTransfer $filterTransfer,
        SchedulerScheduleTransfer $scheduleTransfer,
        array $jobs
    ): SchedulerScheduleTransfer {
        $filteredJobs = $this->jobsFilter->filterJobs($filterTransfer, $jobs);

        foreach ($filteredJobs as $job) {
            $storeNames = $this->getStoreNamesFromJob($job);
            if ($storeNames !== []) {
                $scheduleTransfer = $this->addJobByStore($filterTransfer, $scheduleTransfer, $storeNames, $job);

                continue;
            }

            $jobTransfer = $this->mapJobFromArrayBasedOnStore($job);
            $scheduleTransfer->addJob($jobTransfer);
        }

        return $scheduleTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $filterTransfer
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     * @param array<string> $storeNames
     * @param array $job
     *
     * @return \Generated\Shared\Transfer\SchedulerScheduleTransfer
     */
    protected function addJobByStore(
        SchedulerFilterTransfer $filterTransfer,
        SchedulerScheduleTransfer $scheduleTransfer,
        array $storeNames,
        array $job
    ): SchedulerScheduleTransfer {
        foreach ($storeNames as $storeName) {
            if ($filterTransfer->getStore() && $filterTransfer->getStore() !== $storeName) {
                continue;
            }
            $jobTransfer = $this->mapJobFromArrayBasedOnStore($job, $storeName);
            $scheduleTransfer->addJob($jobTransfer);
        }

        return $scheduleTransfer;
    }

    /**
     * @param array $job
     * @param string|null $storeName
     *
     * @return \Generated\Shared\Transfer\SchedulerJobTransfer
     */
    protected function mapJobFromArrayBasedOnStore(array $job, ?string $storeName = null): SchedulerJobTransfer
    {
        $schedulerJobTransfer = (new SchedulerJobTransfer())
            ->setName($this->getJobName($job, $storeName))
            ->setCommand($job[static::KEY_COMMAND] ?? null)
            ->setEnable($job[static::KEY_ENABLE] ?? false)
            ->setRepeatPattern($job[static::KEY_SCHEDULE] ?? null)
            ->setStore($storeName)
            ->setPayload($this->mapPayloadFromArray($job));

        if ($this->schedulerConfig->isJobRegionRequired()) {
            $schedulerJobTransfer->setRegion($this->schedulerConfig->getCurrentRegion());
        }

        return $schedulerJobTransfer;
    }

    /**
     * @param array $job
     *
     * @return array
     */
    protected function mapPayloadFromArray(array $job): array
    {
        return array_diff_key($job, [
            static::KEY_NAME => '',
            static::KEY_ENABLE => '',
            static::KEY_COMMAND => '',
            static::KEY_SCHEDULE => '',
            static::KEY_STORES => '',
            static::KEY_ROLE => '',
        ]);
    }

    /**
     * @param array $job
     * @param string|null $currentStoreName
     *
     * @return string
     */
    protected function getJobName(array $job, ?string $currentStoreName): string
    {
        $jobNameParts = [];
        if ($this->schedulerConfig->isJobRegionRequired()) {
            $jobNameParts[] = $this->schedulerConfig->getCurrentRegion();
        }
        $jobNameParts[] = $currentStoreName;
        $jobNameParts[] = $job[static::KEY_NAME] ?? '';

        return implode('_', array_filter($jobNameParts));
    }

    /**
     * @param array $job
     *
     * @return array<string>
     */
    protected function getStoreNamesFromJob(array $job): array
    {
        $storeNames = array_key_exists(static::KEY_STORES, $job) ? (array)$job[static::KEY_STORES] : [];

        if (!$storeNames && !$this->isDynamicStoreEnabled()) {
            $storeNames = $this->getStoreList();
        }

        return $storeNames;
    }

    /**
     * @deprecated Exists for BC-reasons only. Will be removed when dynamic store enabled.
     *
     * @return bool
     */
    protected function isDynamicStoreEnabled(): bool
    {
        return Store::isDynamicStoreMode();
    }

    /**
     * @deprecated Exists for BC-reasons only. Will be removed when dynamic store enabled.
     *
     * @return array<string>
     */
    protected function getStoreList(): array
    {
        return Store::getInstance()->getAllowedStores();
    }
}
