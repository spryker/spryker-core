<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business;

use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerScheduleTransfer;

interface SchedulerJenkinsFacadeInterface
{
    /**
     * Specification:
     * - Set ups Jenkins schedulers jobs for the current store.
     * - Set ups Jenkins scheduler jobs by scheduler id for the current store.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function setupJenkins(SchedulerScheduleTransfer $scheduleTransfer): SchedulerResponseTransfer;

    /**
     * Specification:
     * - Cleans Jenkins schedulers jobs for the current store.
     * - Cleans Jenkins scheduler jobs by scheduler id for the current store.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function cleanJenkins(SchedulerScheduleTransfer $scheduleTransfer): SchedulerResponseTransfer;

    /**
     * Specification:
     * - Suspends all Jenkins jobs for the current store..
     * - Suspends selected jobs by name for the current store..
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function suspendJenkins(SchedulerScheduleTransfer $scheduleTransfer): SchedulerResponseTransfer;

    /**
     * Specification:
     * - Resumes all Jenkins jobs for the current store.
     * - Resumes selected jobs by name for the current store.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function resumeJenkins(SchedulerScheduleTransfer $scheduleTransfer): SchedulerResponseTransfer;
}
