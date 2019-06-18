<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business;

use Generated\Shared\Transfer\SchedulerFilterTransfer;
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
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $filterTransfer
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerScheduleTransfer
     */
    public function readScheduleFromPhpSource(
        SchedulerFilterTransfer $filterTransfer,
        SchedulerScheduleTransfer $scheduleTransfer
    ): SchedulerScheduleTransfer {
        return $this->getFactory()
            ->createPhpSchedulerReader()
            ->readSchedule($filterTransfer, $scheduleTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    public function setup(SchedulerFilterTransfer $filterTransfer): SchedulerResponseCollectionTransfer
    {
        return $this->getFactory()
            ->createSchedulerSetup()
            ->execute($filterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    public function clean(SchedulerFilterTransfer $filterTransfer): SchedulerResponseCollectionTransfer
    {
        return $this->getFactory()
            ->createSchedulerClean()
            ->execute($filterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    public function resume(SchedulerFilterTransfer $filterTransfer): SchedulerResponseCollectionTransfer
    {
        return $this->getFactory()
            ->createSchedulerResume()
            ->execute($filterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    public function suspend(SchedulerFilterTransfer $filterTransfer): SchedulerResponseCollectionTransfer
    {
        return $this->getFactory()
            ->createSchedulerSuspend()
            ->execute($filterTransfer);
    }
}
