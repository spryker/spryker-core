<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business;

use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerTransfer;
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
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerTransfer
     */
    public function getPhpCronJobsConfiguration(SchedulerTransfer $schedulerTransfer): SchedulerTransfer
    {
        return $this->getFactory()
            ->createPhpSchedulerReader()
            ->getPhpCronJobsConfiguration($schedulerTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function setup(SchedulerTransfer $schedulerTransfer): SchedulerResponseTransfer
    {
        return $this->getFactory()
            ->createSchedulerSetup()
            ->setup($schedulerTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function clean(SchedulerTransfer $schedulerTransfer): SchedulerResponseTransfer
    {
        return $this->getFactory()
            ->createSchedulerClean()
            ->clean($schedulerTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function resume(SchedulerTransfer $schedulerTransfer): SchedulerResponseTransfer
    {
        return $this->getFactory()
            ->createSchedulerResume()
            ->resume($schedulerTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function suspend(SchedulerTransfer $schedulerTransfer): SchedulerResponseTransfer
    {
        return $this->getFactory()
            ->createSchedulerSuspend()
            ->suspend($schedulerTransfer);
    }
}
