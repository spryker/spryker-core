<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\PhpScheduleReader\Filter;

use Generated\Shared\Transfer\SchedulerFilterTransfer;

interface JobsFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $filterTransfer
     * @param array $jobs
     *
     * @return array $jobs
     */
    public function filterJobs(SchedulerFilterTransfer $filterTransfer, array $jobs): array;
}
