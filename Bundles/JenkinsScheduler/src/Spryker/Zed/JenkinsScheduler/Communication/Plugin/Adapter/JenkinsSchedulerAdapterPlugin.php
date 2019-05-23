<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\JenkinsScheduler\Communication\Plugin\Adapter;

use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SchedulerExtension\Dependency\Adapter\SchedulerAdapterPluginInterface;

/**
 * @method \Spryker\Zed\JenkinsScheduler\Business\JenkinsSchedulerFacadeInterface getFacade()
 * @method \Spryker\Zed\JenkinsScheduler\JenkinsSchedulerConfig getConfig()
 */
class JenkinsSchedulerAdapterPlugin extends AbstractPlugin implements SchedulerAdapterPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerTransfer $scheduleTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function setup(string $schedulerId, SchedulerTransfer $scheduleTransfer, SchedulerResponseTransfer $schedulerResponseTransfer): SchedulerResponseTransfer
    {
        return $this->getFacade()->setupJenkinsScheduler($schedulerId, $scheduleTransfer, $schedulerResponseTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerTransfer $scheduleTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function clean(string $schedulerId, SchedulerTransfer $scheduleTransfer, SchedulerResponseTransfer $schedulerResponseTransfer): SchedulerResponseTransfer
    {
        return $this->getFacade()->cleanJenkinsScheduler($schedulerId, $scheduleTransfer, $schedulerResponseTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerTransfer $scheduleTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function suspend(string $schedulerId, SchedulerTransfer $scheduleTransfer, SchedulerResponseTransfer $schedulerResponseTransfer): SchedulerResponseTransfer
    {
        return $this->getFacade()->suspendJenkinsScheduler($schedulerId, $scheduleTransfer, $schedulerResponseTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerTransfer $scheduleTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function resume(string $schedulerId, SchedulerTransfer $scheduleTransfer, SchedulerResponseTransfer $schedulerResponseTransfer): SchedulerResponseTransfer
    {
        return $this->getFacade()->resumeJenkinsScheduler($schedulerId, $scheduleTransfer, $schedulerResponseTransfer);
    }
}
