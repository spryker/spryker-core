<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerExtension\Dependency\Plugin;

use Generated\Shared\Transfer\SchedulerTransfer;

interface SchedulerReaderPluginInterface
{
    /**
     * Specification:
     * - Reads configuration from the provided sources.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerTransfer
     */
    public function readSchedule(SchedulerTransfer $schedulerTransfer): SchedulerTransfer;
}
