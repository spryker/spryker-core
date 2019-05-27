<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\Command\Filter;

use Generated\Shared\Transfer\SchedulerFilterTransfer;

interface SchedulerFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $schedulerFilterTransfer
     * @param string[] $allSchedulerKeys
     *
     * @return array
     */
    public function filterSchedulers(SchedulerFilterTransfer $schedulerFilterTransfer, array $allSchedulerKeys): array;
}
