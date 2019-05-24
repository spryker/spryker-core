<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Setup;

use Generated\Shared\Transfer\SchedulerRequestTransfer;
use Generated\Shared\Transfer\SchedulerResponseCollectionTransfer;

interface SchedulerJenkinsSetupInterface
{
    /**
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerRequestTransfer $scheduleTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    public function setup(string $schedulerId, SchedulerRequestTransfer $scheduleTransfer, SchedulerResponseCollectionTransfer $schedulerResponseTransfer): SchedulerResponseCollectionTransfer;
}
