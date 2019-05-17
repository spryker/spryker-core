<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerExtension\Dependency\Adapter;

use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerTransfer;

interface SchedulerAdapterPluginInterface
{
    /**
     * Specification:
     * - Checks whether this adapter plugin is applicable for the execution.
     *
     * @api
     *
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerTransfer $scheduleTransfer
     *
     * @return bool
     */
    public function isApplicable(string $schedulerId, SchedulerTransfer $scheduleTransfer): bool;

    /**
     * Specification:
     * - Cleans scheduler(s) job(s) for the current store.
     *
     * @api
     *
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerTransfer $scheduleTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function setup(string $schedulerId, SchedulerTransfer $scheduleTransfer, SchedulerResponseTransfer $schedulerResponseTransfer): SchedulerResponseTransfer;

    /**
     * Specification:
     * - Cleans scheduler(s) job(s) for the current store.
     *
     * @api
     *
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerTransfer $scheduleTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function clean(string $schedulerId, SchedulerTransfer $scheduleTransfer, SchedulerResponseTransfer $schedulerResponseTransfer): SchedulerResponseTransfer;

    /**
     * Specification:
     * - Suspends all jobs for the current store.
     * - Suspends jobs by name for the current store.
     *
     * @api
     *
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerTransfer $scheduleTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function suspend(string $schedulerId, SchedulerTransfer $scheduleTransfer, SchedulerResponseTransfer $schedulerResponseTransfer): SchedulerResponseTransfer;

    /**
     * Specification:
     * - Resumes all jobs for the current store.
     * - Resumes jobs by name for the current store.
     *
     * @api
     *
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerTransfer $scheduleTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function resume(string $schedulerId, SchedulerTransfer $scheduleTransfer, SchedulerResponseTransfer $schedulerResponseTransfer): SchedulerResponseTransfer;
}
