<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Iterator\Builder;

use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerScheduleTransfer;

class SchedulerResponseBuilder implements SchedulerResponseBuilderInterface
{
    /**
     * @var \Generated\Shared\Transfer\SchedulerScheduleTransfer|null
     */
    protected $schedulerTransfer;

    /**
     * @var bool
     */
    protected $status = false;

    /**
     * @var string
     */
    protected $message = '';

    /**
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function build(): SchedulerResponseTransfer
    {
        $schedulerResponseTransfer = new SchedulerResponseTransfer();

        $schedulerResponseTransfer
            ->setSchedule($this->schedulerTransfer ?? new SchedulerScheduleTransfer())
            ->setStatus($this->status)
            ->setMessage($this->message);

        return $schedulerResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     *
     * @return static
     */
    public function withSchedule(SchedulerScheduleTransfer $scheduleTransfer)
    {
        $this->schedulerTransfer = $scheduleTransfer;

        return $this;
    }

    /**
     * @param bool $status
     *
     * @return static
     */
    public function withStatus(bool $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @param string $message
     *
     * @return static
     */
    public function withMessage(string $message)
    {
        $this->message = $message;

        return $this;
    }
}
