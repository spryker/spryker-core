<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\PhpScheduleReader\Filter;

use Generated\Shared\Transfer\SchedulerFilterTransfer;

abstract class AbstractJobsFilter
{
    /**
     * @var \Spryker\Zed\Scheduler\Business\PhpScheduleReader\Filter\JobsFilterInterface|null
     */
    protected $nextFilter;

    /**
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $filterTransfer
     * @param array $jobs
     *
     * @return array
     */
    public function next(SchedulerFilterTransfer $filterTransfer, array $jobs): array
    {
        return $this->nextFilter !== null ? $this->nextFilter->filterJobs($filterTransfer, $jobs) : $jobs;
    }

    /**
     * @param \Spryker\Zed\Scheduler\Business\PhpScheduleReader\Filter\JobsFilterInterface $nextFilter
     *
     * @return static
     */
    public function setNextFilter(JobsFilterInterface $nextFilter)
    {
        $this->nextFilter = $nextFilter;

        return $this;
    }
}
