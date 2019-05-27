<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business;

use Generated\Shared\Transfer\SchedulerFilterTransfer;
use Generated\Shared\Transfer\SchedulerRequestTransfer;
use Generated\Shared\Transfer\SchedulerResponseCollectionTransfer;
use Generated\Shared\Transfer\SchedulerScheduleTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Scheduler\Business\SchedulerBusinessFactory getFactory()
 */
class SchedulerFacade extends AbstractFacade implements SchedulerFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $schedulerFilterTransfer
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerScheduleTransfer
     */
    public function readScheduleFromPhpSource(SchedulerFilterTransfer $schedulerFilterTransfer, SchedulerScheduleTransfer $scheduleTransfer): SchedulerScheduleTransfer
    {
        return $this->getFactory()
            ->createPhpSchedulerReader()
            ->readSchedule($schedulerFilterTransfer, $scheduleTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $schedulerFilterTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    public function setup(SchedulerFilterTransfer $schedulerFilterTransfer): SchedulerResponseCollectionTransfer
    {
        return $this->getFactory()
            ->createSchedulerSetup()
            ->execute($schedulerFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $schedulerFilterTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    public function clean(SchedulerFilterTransfer $schedulerFilterTransfer): SchedulerResponseCollectionTransfer
    {
        return $this->getFactory()
            ->createSchedulerClean()
            ->execute($schedulerFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $schedulerFilterTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    public function resume(SchedulerFilterTransfer $schedulerFilterTransfer): SchedulerResponseCollectionTransfer
    {
        return $this->getFactory()
            ->createSchedulerResume()
            ->execute($schedulerFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $schedulerFilterTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    public function suspend(SchedulerFilterTransfer $schedulerFilterTransfer): SchedulerResponseCollectionTransfer
    {
        return $this->getFactory()
            ->createSchedulerSuspend()
            ->execute($schedulerFilterTransfer);
    }
}
