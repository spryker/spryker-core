<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\PhpScheduleReader\Filter;

use Generated\Shared\Transfer\SchedulerFilterTransfer;

class JobsFilterByName extends AbstractJobsFilter implements ChainableJobsFilterInterface
{
    /**
     * @see \Spryker\Zed\Scheduler\Business\PhpScheduleReader\Mapper\PhpScheduleMapper::KEY_NAME
     */
    protected const KEY_NAME = 'name';

    /**
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $filterTransfer
     * @param array $jobs
     *
     * @return array
     */
    public function filterJobs(SchedulerFilterTransfer $filterTransfer, array $jobs): array
    {
        $jobsToFilter = $filterTransfer->getJobs();

        if (count($jobsToFilter) === 0) {
            return $this->next($filterTransfer, $jobs);
        }

        $filteredJobs = [];

        foreach ($jobs as $job) {
            if (in_array($job[static::KEY_NAME], $jobsToFilter, true)) {
                $filteredJobs[] = $job;
            }
        }

        return $this->next($filterTransfer, $filteredJobs);
    }
}
