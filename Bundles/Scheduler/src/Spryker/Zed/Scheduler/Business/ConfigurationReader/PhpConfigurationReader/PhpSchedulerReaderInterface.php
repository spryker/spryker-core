<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\ConfigurationReader\PhpConfigurationReader;

use Generated\Shared\Transfer\SchedulerTransfer;

interface PhpSchedulerReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerTransfer
     */
    public function getPhpCronJobsConfiguration(SchedulerTransfer $schedulerTransfer): SchedulerTransfer;
}
