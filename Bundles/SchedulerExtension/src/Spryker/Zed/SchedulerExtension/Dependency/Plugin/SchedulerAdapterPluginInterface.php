<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerExtension\Dependency\Plugin;

use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerScheduleTransfer;

interface SchedulerAdapterPluginInterface
{
    /**
     * Specification:
     * - Cleans scheduler(s) job(s) for the current store.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $schedulerScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function setup(SchedulerScheduleTransfer $schedulerScheduleTransfer): SchedulerResponseTransfer;

    /**
     * Specification:
     * - Cleans scheduler(s) job(s) for the current store.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $schedulerScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function clean(SchedulerScheduleTransfer $schedulerScheduleTransfer): SchedulerResponseTransfer;

    /**
     * Specification:
     * - Suspends all jobs for the current store.
     * - Suspends jobs by name for the current store.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $schedulerScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function suspend(SchedulerScheduleTransfer $schedulerScheduleTransfer): SchedulerResponseTransfer;

    /**
     * Specification:
     * - Resumes all jobs for the current store.
     * - Resumes jobs by name for the current store.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $schedulerScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function resume(SchedulerScheduleTransfer $schedulerScheduleTransfer): SchedulerResponseTransfer;
}
