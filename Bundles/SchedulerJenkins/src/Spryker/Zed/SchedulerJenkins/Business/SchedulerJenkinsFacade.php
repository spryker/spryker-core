<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business;

use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerScheduleTransfer;
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
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function setupJenkins(SchedulerScheduleTransfer $scheduleTransfer): SchedulerResponseTransfer
    {
        return $this->getFactory()
            ->createSchedulerJenkinsSetup()
            ->processSchedule($scheduleTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function cleanJenkins(SchedulerScheduleTransfer $scheduleTransfer): SchedulerResponseTransfer
    {
        return $this->getFactory()
            ->createSchedulerJenkinsClean()
            ->processSchedule($scheduleTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function suspendJenkins(SchedulerScheduleTransfer $scheduleTransfer): SchedulerResponseTransfer
    {
        return $this->getFactory()
            ->createSchedulerJenkinsDisable()
            ->processSchedule($scheduleTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function resumeJenkins(SchedulerScheduleTransfer $scheduleTransfer): SchedulerResponseTransfer
    {
        return $this->getFactory()
            ->createSchedulerJenkinsEnable()
            ->processSchedule($scheduleTransfer);
    }
}
