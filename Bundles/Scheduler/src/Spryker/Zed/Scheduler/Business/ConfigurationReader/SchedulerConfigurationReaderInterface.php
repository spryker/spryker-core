<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\ConfigurationReader;

use Generated\Shared\Transfer\SchedulerTransfer;

interface SchedulerConfigurationReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerTransfer
     */
    public function getCronJobsConfiguration(SchedulerTransfer $schedulerTransfer): SchedulerTransfer;
}
