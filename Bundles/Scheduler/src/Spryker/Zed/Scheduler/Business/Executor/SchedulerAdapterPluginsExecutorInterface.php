<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\Executor;

use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerTransfer;

interface SchedulerAdapterPluginsExecutorInterface
{
    /**
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerTransfer
     */
    public function executeSchedulerReaderPlugins(SchedulerTransfer $schedulerTransfer): SchedulerTransfer;

    /**
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function executeSchedulerAdapterPluginsForSchedulerSetup(SchedulerTransfer $schedulerTransfer): SchedulerResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function executeSchedulerAdapterPluginsForSchedulerClean(SchedulerTransfer $schedulerTransfer): SchedulerResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function executeSchedulerAdapterPluginsForSchedulerResume(SchedulerTransfer $schedulerTransfer): SchedulerResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function executeSchedulerAdapterPluginsForSchedulerSuspend(SchedulerTransfer $schedulerTransfer): SchedulerResponseTransfer;
}
