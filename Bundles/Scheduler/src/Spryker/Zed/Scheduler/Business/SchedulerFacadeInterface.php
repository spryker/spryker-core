<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business;

use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerTransfer;

interface SchedulerFacadeInterface
{
    /**
     * Specification:
     * - Reads schedulers configuration from the *.php source.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerTransfer
     */
    public function getPhpCronJobsConfiguration(SchedulerTransfer $schedulerTransfer): SchedulerTransfer;

    /**
     * Specification:
     * - Set ups schedulers jobs for the current store.
     * - Set ups scheduler jobs for the current store by scheduler identifier.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function setup(SchedulerTransfer $schedulerTransfer): SchedulerResponseTransfer;

    /**
     * Specification:
     * - Cleans schedulers jobs for the current store.
     * - Clean scheduler jobs for the current store by scheduler identifier.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function clean(SchedulerTransfer $schedulerTransfer): SchedulerResponseTransfer;

    /**
     * Specification:
     * - Resumes all scheduler(s) jobs.
     * - Resumes scheduler jobs by provided job name(s).
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function resume(SchedulerTransfer $schedulerTransfer): SchedulerResponseTransfer;

    /**
     * Specification:
     * - Suspends all scheduler(s) jobs.
     * - Suspends scheduler jobs by provided job name(s).
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function suspend(SchedulerTransfer $schedulerTransfer): SchedulerResponseTransfer;
}
