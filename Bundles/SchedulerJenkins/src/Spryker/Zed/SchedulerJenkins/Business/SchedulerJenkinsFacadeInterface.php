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
     * - Setup jobs for the Jenkins scheduler according the given schedule.
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
     * - Cleans jobs for the Jenkins scheduler according the given schedule.
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
     * - Suspends jobs for the Jenkins scheduler according the given schedule.s
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
     * - Resumes jobs for the Jenkins scheduler according the given schedule.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function resumeJenkins(SchedulerScheduleTransfer $scheduleTransfer): SchedulerResponseTransfer;
}
