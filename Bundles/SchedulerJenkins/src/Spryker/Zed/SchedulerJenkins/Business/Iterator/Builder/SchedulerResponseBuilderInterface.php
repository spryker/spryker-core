<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Iterator\Builder;

use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerScheduleTransfer;

interface SchedulerResponseBuilderInterface
{
    /**
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function build(): SchedulerResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\SchedulerScheduleTransfer $scheduleTransfer
     *
     * @return static
     */
    public function withSchedule(SchedulerScheduleTransfer $scheduleTransfer);

    /**
     * @param bool $status
     *
     * @return static
     */
    public function withStatus(bool $status);

    /**
     * @param string $message
     *
     * @return static
     */
    public function withMessage(string $message);
}
