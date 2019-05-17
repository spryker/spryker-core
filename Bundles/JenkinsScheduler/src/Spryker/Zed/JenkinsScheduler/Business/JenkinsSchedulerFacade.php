<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\JenkinsScheduler\Business;

use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\JenkinsScheduler\Business\JenkinsSchedulerBusinessFactory getFactory()
 */
class JenkinsSchedulerFacade extends AbstractFacade implements JenkinsSchedulerFacadeInterface
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
    public function setupJenkinsScheduler(string $schedulerId, SchedulerTransfer $scheduleTransfer, SchedulerResponseTransfer $schedulerResponseTransfer): SchedulerResponseTransfer
    {
        return $this->getFactory()
            ->createJenkinsSchedulerSetup()
            ->setup($schedulerId, $scheduleTransfer, $schedulerResponseTransfer);
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
    public function cleanJenkinsScheduler(string $schedulerId, SchedulerTransfer $scheduleTransfer, SchedulerResponseTransfer $schedulerResponseTransfer): SchedulerResponseTransfer
    {
        return $this->getFactory()
            ->createJenkinsJobClean()
            ->cleanJenkinsScheduler($schedulerId, $scheduleTransfer, $schedulerResponseTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function suspendJenkinsScheduler(string $schedulerId, SchedulerTransfer $schedulerTransfer, SchedulerResponseTransfer $schedulerResponseTransfer): SchedulerResponseTransfer
    {
        return $this->getFactory()
            ->createJenkinsSuspend()
            ->suspendJenkinsScheduler($schedulerId, $schedulerTransfer, $schedulerResponseTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function resumeJenkinsScheduler(string $schedulerId, SchedulerTransfer $schedulerTransfer, SchedulerResponseTransfer $schedulerResponseTransfer): SchedulerResponseTransfer
    {
        return $this->getFactory()
            ->createJenkinsResume()
            ->resumeJenkinsScheduler($schedulerId, $schedulerTransfer, $schedulerResponseTransfer);
    }
}
