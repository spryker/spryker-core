<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Executor;

use Generated\Shared\Transfer\SchedulerJobTransfer;
use Generated\Shared\Transfer\SchedulerResponseTransfer;

interface ExecutorInterface
{
    /**
     * @param string $idScheduler
     * @param \Generated\Shared\Transfer\SchedulerJobTransfer $schedulerJobTransfer
     *
     * @throws \Spryker\Zed\SchedulerJenkins\Business\Api\Exception\JenkinsBaseUrlNotFound
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function execute(string $idScheduler, SchedulerJobTransfer $schedulerJobTransfer): SchedulerResponseTransfer;
}
