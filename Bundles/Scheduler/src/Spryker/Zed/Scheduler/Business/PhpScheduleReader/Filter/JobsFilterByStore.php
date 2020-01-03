<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\PhpScheduleReader\Filter;

use Generated\Shared\Transfer\SchedulerFilterTransfer;

class JobsFilterByStore extends AbstractJobsFilter implements ChainableJobsFilterInterface
{
    /**
     * @see \Spryker\Zed\Scheduler\Business\PhpScheduleReader\Mapper\PhpScheduleMapper::KEY_STORES
     */
    protected const KEY_STORES = 'stores';

    /**
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $filterTransfer
     * @param array $jobs
     *
     * @return array
     */
    public function filterJobs(SchedulerFilterTransfer $filterTransfer, array $jobs): array
    {
        $storeName = $filterTransfer->getStore();

        $filteredJobs = [];

        foreach ($jobs as $job) {
            $jobStores = $this->getStoreNamesFromJob($job);

            if (in_array($storeName, $jobStores, true)) {
                $filteredJobs[] = $job;
            }
        }

        return $this->next($filterTransfer, $filteredJobs);
    }

    /**
     * @param array $job
     *
     * @return array
     */
    protected function getStoreNamesFromJob(array $job): array
    {
        return array_key_exists(static::KEY_STORES, $job) ? (array)$job[static::KEY_STORES] : [];
    }
}
