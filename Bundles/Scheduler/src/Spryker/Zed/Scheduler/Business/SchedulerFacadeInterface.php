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
     * - Reads schedule for particular scheduler and for the current store from PHP source.
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
     * - Sets up jobs for given schedulers and for the current store.
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
     * - Cleans jobs for given schedulers and for the current store.
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
     * - Resumes all scheduler(s) jobs for the current store.
     * - Resumes scheduler jobs by provided job name(s) if defined.
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
     * - Suspends all scheduler(s) jobs for the current store.
     * - Suspends scheduler jobs by provided job name(s) if defined.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    public function suspend(SchedulerFilterTransfer $filterTransfer): SchedulerResponseCollectionTransfer;
}
