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
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function setup(SchedulerScheduleTransfer $scheduleTransfer): SchedulerResponseTransfer
    {
        return $this->getFacade()->setupSchedulerJenkins($scheduleTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function clean(SchedulerScheduleTransfer $scheduleTransfer): SchedulerResponseTransfer
    {
        return $this->getFacade()->cleanSchedulerJenkins($scheduleTransfer);
    }

    /**
     * {@inheritdoc}
     * d
     * @api
     *
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function suspend(SchedulerScheduleTransfer $scheduleTransfer): SchedulerResponseTransfer
    {
        return $this->getFacade()->suspendSchedulerJenkins($scheduleTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function resume(SchedulerScheduleTransfer $scheduleTransfer): SchedulerResponseTransfer
    {
        return $this->getFacade()->resumeSchedulerJenkins($scheduleTransfer);
    }
}
