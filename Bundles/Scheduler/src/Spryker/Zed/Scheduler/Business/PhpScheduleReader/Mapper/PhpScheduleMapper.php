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

class PhpScheduleMapper implements PhpScheduleMapperInterface
{
    protected const KEY_NAME = 'name';
    protected const KEY_ENABLE = 'enable';
    protected const KEY_COMMAND = 'command';
    protected const KEY_SCHEDULE = 'schedule';
    protected const KEY_STORES = 'stores';
    protected const KEY_ROLE = 'role';

    /**
     * @var \Spryker\Zed\Scheduler\Business\PhpScheduleReader\Filter\JobsFilterInterface
     */
    protected $jobsFilter;

    /**
     * @param \Spryker\Zed\Scheduler\Business\PhpScheduleReader\Filter\JobsFilterInterface $jobsFilter
     */
    public function __construct(JobsFilterInterface $jobsFilter)
    {
        $this->jobsFilter = $jobsFilter;
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
        $filterTransfer->requireStore();

        $storeName = $filterTransfer->getStore();
        $filteredJobs = $this->jobsFilter->filterJobs($filterTransfer, $jobs);

        foreach ($filteredJobs as $job) {
            $jobTransfer = $this->mapJobFromArrayBasedOnStore($job, $storeName);
            $scheduleTransfer->addJob($jobTransfer);
        }

        return $scheduleTransfer;
    }

    /**
     * @param array $job
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\SchedulerJobTransfer
     */
    protected function mapJobFromArrayBasedOnStore(array $job, string $storeName): SchedulerJobTransfer
    {
        return (new SchedulerJobTransfer())
            ->setName($this->getJobName($job, $storeName))
            ->setCommand($job[static::KEY_COMMAND] ?? null)
            ->setEnable($job[static::KEY_ENABLE] ?? false)
            ->setRepeatPattern($job[static::KEY_SCHEDULE] ?? null)
            ->setStore($storeName)
            ->setPayload($this->mapPayloadFromArray($job));
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
     * @param string $currentStoreName
     *
     * @return string
     */
    protected function getJobName(array $job, string $currentStoreName): string
    {
        return sprintf('%s__%s', $currentStoreName, $job[static::KEY_NAME] ?? '');
    }
}
