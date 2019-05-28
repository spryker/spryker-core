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
     * @var bool
     */
    protected $status;

    /**
     * @var \Generated\Shared\Transfer\SchedulerScheduleTransfer
     */
    protected $schedulerTransfer;

    /**
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function build(): SchedulerResponseTransfer
    {
        $schedulerResponseTransfer = new SchedulerResponseTransfer();

        $schedulerResponseTransfer
            ->setSchedule($this->schedulerTransfer)
            ->setStatus($this->status);

        return $schedulerResponseTransfer;
    }

    /**
     * @param bool $status
     *
     * @return $this
     */
    public function withStatus(bool $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $schedulerTransfer
     *
     * @return $this
     */
    public function withScheduler(SchedulerScheduleTransfer $schedulerTransfer)
    {
        $this->schedulerTransfer = $schedulerTransfer;

        return $this;
    }
}
