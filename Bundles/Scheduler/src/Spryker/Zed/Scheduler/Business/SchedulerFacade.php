<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business;

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
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerScheduleTransfer
     */
    public function readScheduleFromPhpSource(SchedulerScheduleTransfer $scheduleTransfer): SchedulerScheduleTransfer
    {
        return $this->getFactory()
            ->createPhpSchedulerReader()
            ->readSchedule($scheduleTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerRequestTransfer $schedulerRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    public function setup(SchedulerRequestTransfer $schedulerRequestTransfer): SchedulerResponseCollectionTransfer
    {
        return $this->getFactory()
            ->createSchedulerSetup()
            ->execute($schedulerRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerRequestTransfer $schedulerRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    public function clean(SchedulerRequestTransfer $schedulerRequestTransfer): SchedulerResponseCollectionTransfer
    {
        return $this->getFactory()
            ->createSchedulerClean()
            ->execute($schedulerRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerRequestTransfer $schedulerRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    public function resume(SchedulerRequestTransfer $schedulerRequestTransfer): SchedulerResponseCollectionTransfer
    {
        return $this->getFactory()
            ->createSchedulerResume()
            ->execute($schedulerRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerRequestTransfer $schedulerRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    public function suspend(SchedulerRequestTransfer $schedulerRequestTransfer): SchedulerResponseCollectionTransfer
    {
        return $this->getFactory()
            ->createSchedulerSuspend()
            ->execute($schedulerRequestTransfer);
    }
}
