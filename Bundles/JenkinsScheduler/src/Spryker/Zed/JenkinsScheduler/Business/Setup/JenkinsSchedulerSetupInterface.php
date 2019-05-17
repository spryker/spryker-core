<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\JenkinsScheduler\Business\Setup;

use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerTransfer;

interface JenkinsSchedulerSetupInterface
{
    /**
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function setup(string $schedulerId, SchedulerTransfer $schedulerTransfer, SchedulerResponseTransfer $schedulerResponseTransfer): SchedulerResponseTransfer;
}
