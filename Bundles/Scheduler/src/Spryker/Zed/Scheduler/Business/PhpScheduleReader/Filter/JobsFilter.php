<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\PhpScheduleReader\Filter;

use Generated\Shared\Transfer\SchedulerFilterTransfer;

class JobsFilter implements JobsFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $schedulerFilterTransfer
     * @param array $jobs
     *
     * @return array
     */
    public function filterJobsByName(SchedulerFilterTransfer $schedulerFilterTransfer, array $jobs): array
    {
        $jobsToFilter = $schedulerFilterTransfer->getJobs();

        if ($jobsToFilter === []) {
            return $jobs;
        }

        $filteredJobs = [];

        foreach ($jobs as $job) {
            if (in_array($job['name'], $jobsToFilter)) {
                $filteredJobs[] = $job;
            }
        }

        return $filteredJobs;
    }
}
