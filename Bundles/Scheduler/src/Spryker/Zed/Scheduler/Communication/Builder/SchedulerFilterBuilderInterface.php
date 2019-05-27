<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Communication\Builder;

use Generated\Shared\Transfer\SchedulerFilterTransfer;

interface SchedulerFilterBuilderInterface
{
    /**
     * @return \Generated\Shared\Transfer\SchedulerFilterTransfer
     */
    public function build(): SchedulerFilterTransfer;

    /**
     * @param array $schedulerIds
     *
     * @return $this
     */
    public function withSchedulerIds(array $schedulerIds);

    /**
     * @param array $jobNames
     *
     * @return $this
     */
    public function withJobNames(array $jobNames);
}
