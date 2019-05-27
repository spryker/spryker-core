<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Communication\Builder;

use Generated\Shared\Transfer\SchedulerFilterTransfer;

class SchedulerFilterBuilder implements SchedulerFilterBuilderInterface
{
    /**
     * @var array
     */
    protected $schedulerIds = [];

    /**
     * @var array
     */
    protected $jobNames = [];

    /**
     * @return \Generated\Shared\Transfer\SchedulerFilterTransfer
     */
    public function build(): SchedulerFilterTransfer
    {
        $schedulerFilterTransfer = new SchedulerFilterTransfer();

        $schedulerFilterTransfer
            ->setSchedulers($this->schedulerIds)
            ->setJobs($this->jobNames);

        return $schedulerFilterTransfer;
    }

    /**
     * @param array $schedulerIds
     *
     * @return $this
     */
    public function withSchedulerIds(array $schedulerIds)
    {
        $this->schedulerIds = $schedulerIds;

        return $this;
    }

    /**
     * @param array $jobNames
     *
     * @return $this
     */
    public function withJobNames(array $jobNames)
    {
        $this->jobNames = $jobNames;

        return $this;
    }
}
