<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\Command;

use Generated\Shared\Transfer\SchedulerFilterTransfer;
use Generated\Shared\Transfer\SchedulerResponseCollectionTransfer;

interface SchedulerCommandInterface
{
    /**
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    public function execute(SchedulerFilterTransfer $filterTransfer): SchedulerResponseCollectionTransfer;
}
