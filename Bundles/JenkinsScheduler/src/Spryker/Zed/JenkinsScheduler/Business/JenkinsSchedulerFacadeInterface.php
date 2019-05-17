<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\JenkinsScheduler\Business;

use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerTransfer;

interface JenkinsSchedulerFacadeInterface
{
    /**
     * Specification:
     * - Set ups Jenkins schedulers jobs for the current store.
     * - Set ups Jenkins scheduler jobs by scheduler id for the current store.
     *
     * @api
     *
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerTransfer $scheduleTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function setupJenkinsScheduler(string $schedulerId, SchedulerTransfer $scheduleTransfer, SchedulerResponseTransfer $schedulerResponseTransfer): SchedulerResponseTransfer;

    /**
     * Specification:
     * - Cleans Jenkins schedulers jobs for the current store.
     * - Cleans Jenkins scheduler jobs by scheduler id for the current store.
     *
     * @api
     *
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerTransfer $scheduleTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function cleanJenkinsScheduler(string $schedulerId, SchedulerTransfer $scheduleTransfer, SchedulerResponseTransfer $schedulerResponseTransfer): SchedulerResponseTransfer;

    /**
     * Specification:
     * - Suspends all Jenkins jobs for the current store..
     * - Suspends selected jobs by name for the current store..
     *
     * @api
     *
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerTransfer $scheduleTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function suspendJenkinsScheduler(string $schedulerId, SchedulerTransfer $scheduleTransfer, SchedulerResponseTransfer $schedulerResponseTransfer): SchedulerResponseTransfer;

    /**
     * Specification:
     * - Resumes all Jenkins jobs for the current store.
     * - Resumes selected jobs by name for the current store.
     *
     * @api
     *
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerTransfer $scheduleTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function resumeJenkinsScheduler(string $schedulerId, SchedulerTransfer $scheduleTransfer, SchedulerResponseTransfer $schedulerResponseTransfer): SchedulerResponseTransfer;
}
