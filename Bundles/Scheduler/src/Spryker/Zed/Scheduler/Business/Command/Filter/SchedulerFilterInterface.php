<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\Command\Filter;

use Generated\Shared\Transfer\SchedulerRequestTransfer;

interface SchedulerFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\SchedulerRequestTransfer $schedulerRequestTransfer
     * @param string[] $allSchedulerKeys
     *
     * @return array
     */
    public function filterSchedulers(SchedulerRequestTransfer $schedulerRequestTransfer, array $allSchedulerKeys): array;
}
