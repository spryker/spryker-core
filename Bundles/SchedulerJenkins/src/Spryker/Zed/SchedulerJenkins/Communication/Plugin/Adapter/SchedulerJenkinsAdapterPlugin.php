<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Communication\Plugin\Adapter;

use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerScheduleTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SchedulerExtension\Dependency\Plugin\SchedulerAdapterPluginInterface;

/**
 * @method \Spryker\Zed\SchedulerJenkins\Business\SchedulerJenkinsFacadeInterface getFacade()
 * @method \Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig getConfig()
 */
class SchedulerJenkinsAdapterPlugin extends AbstractPlugin implements SchedulerAdapterPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $schedulerScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function setup(SchedulerScheduleTransfer $schedulerScheduleTransfer): SchedulerResponseTransfer
    {
        return $this->getFacade()->setupJenkins($schedulerScheduleTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $schedulerScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function clean(SchedulerScheduleTransfer $schedulerScheduleTransfer): SchedulerResponseTransfer
    {
        return $this->getFacade()->cleanJenkins($schedulerScheduleTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $schedulerScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function suspend(SchedulerScheduleTransfer $schedulerScheduleTransfer): SchedulerResponseTransfer
    {
        return $this->getFacade()->suspendJenkins($schedulerScheduleTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $schedulerScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function resume(SchedulerScheduleTransfer $schedulerScheduleTransfer): SchedulerResponseTransfer
    {
        return $this->getFacade()->resumeJenkins($schedulerScheduleTransfer);
    }
}
