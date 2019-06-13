<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business;

use Generated\Shared\Transfer\SchedulerFilterTransfer;
use Generated\Shared\Transfer\SchedulerResponseCollectionTransfer;
use Generated\Shared\Transfer\SchedulerScheduleTransfer;

interface SchedulerFacadeInterface
{
    /**
     * Specification:
     * - Read jobs from PHP source for the scheduler defined in the given schedule.
     * - Perform jobs filtration according the given filter.
     * - Extends the given Schedule with the jobs after filtration.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $filterTransfer
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerScheduleTransfer
     */
    public function readScheduleFromPhpSource(
        SchedulerFilterTransfer $filterTransfer,
        SchedulerScheduleTransfer $scheduleTransfer
    ): SchedulerScheduleTransfer;

    /**
     * Specification:
     * - Sets up jobs for all enabled schedulers according given filter.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    public function setup(SchedulerFilterTransfer $filterTransfer): SchedulerResponseCollectionTransfer;

    /**
     * Specification:
     * - Cleans jobs for all enabled schedulers according given filter.s
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    public function clean(SchedulerFilterTransfer $filterTransfer): SchedulerResponseCollectionTransfer;

    /**
     * Specification:
     * - Resumes jobs for all enabled schedulers according given filter.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    public function resume(SchedulerFilterTransfer $filterTransfer): SchedulerResponseCollectionTransfer;

    /**
     * Specification:
     * - Suspends jobs for all enabled schedulers according given filter.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    public function suspend(SchedulerFilterTransfer $filterTransfer): SchedulerResponseCollectionTransfer;
}
