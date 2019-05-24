<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business;

use Generated\Shared\Transfer\SchedulerRequestTransfer;
use Generated\Shared\Transfer\SchedulerResponseCollectionTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SchedulerJenkins\Business\SchedulerJenkinsBusinessFactory getFactory()
 */
class SchedulerJenkinsFacade extends AbstractFacade implements SchedulerJenkinsFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerRequestTransfer $scheduleTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    public function setupSchedulerJenkins(string $schedulerId, SchedulerRequestTransfer $scheduleTransfer, SchedulerResponseCollectionTransfer $schedulerResponseTransfer): SchedulerResponseCollectionTransfer
    {
        return $this->getFactory()
            ->createSchedulerJenkinsSetup()
            ->setup($schedulerId, $scheduleTransfer, $schedulerResponseTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerRequestTransfer $scheduleTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    public function cleanSchedulerJenkins(string $schedulerId, SchedulerRequestTransfer $scheduleTransfer, SchedulerResponseCollectionTransfer $schedulerResponseTransfer): SchedulerResponseCollectionTransfer
    {
        return $this->getFactory()
            ->createJenkinsJobClean()
            ->cleanSchedulerJenkins($schedulerId, $scheduleTransfer, $schedulerResponseTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerRequestTransfer $scheduleTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    public function suspendSchedulerJenkins(string $schedulerId, SchedulerRequestTransfer $scheduleTransfer, SchedulerResponseCollectionTransfer $schedulerResponseTransfer): SchedulerResponseCollectionTransfer
    {
        return $this->getFactory()
            ->createJenkinsSuspend()
            ->suspendSchedulerJenkins($schedulerId, $scheduleTransfer, $schedulerResponseTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $schedulerId
     * @param \Generated\Shared\Transfer\SchedulerRequestTransfer $scheduleTransfer
     * @param \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer $schedulerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    public function resumeSchedulerJenkins(string $schedulerId, SchedulerRequestTransfer $scheduleTransfer, SchedulerResponseCollectionTransfer $schedulerResponseTransfer): SchedulerResponseCollectionTransfer
    {
        return $this->getFactory()
            ->createJenkinsResume()
            ->resumeSchedulerJenkins($schedulerId, $scheduleTransfer, $schedulerResponseTransfer);
    }
}
