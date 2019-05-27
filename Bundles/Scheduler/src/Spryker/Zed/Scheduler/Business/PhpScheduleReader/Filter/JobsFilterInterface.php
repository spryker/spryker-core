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
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $schedulerFilterTransfer
     * @param array $jobs
     *
     * @return array $jobs
     */
    public function filterJobsByName(SchedulerFilterTransfer $schedulerFilterTransfer, array $jobs): array;
}
