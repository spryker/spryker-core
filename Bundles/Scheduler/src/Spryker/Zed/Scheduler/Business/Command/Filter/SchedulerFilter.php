<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\Command\Filter;

use Generated\Shared\Transfer\SchedulerFilterTransfer;
use Generated\Shared\Transfer\SchedulerRequestTransfer;
use Spryker\Zed\Scheduler\SchedulerConfig;

class SchedulerFilter implements SchedulerFilterInterface
{
    /**
     * @var \Spryker\Zed\Scheduler\SchedulerConfig
     */
    protected $schedulerConfig;

    /**
     * @param \Spryker\Zed\Scheduler\SchedulerConfig $schedulerConfig
     */
    public function __construct(SchedulerConfig $schedulerConfig)
    {
        $this->schedulerConfig = $schedulerConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $schedulerFilterTransfer
     * @param string[] $allSchedulerKeys
     *
     * @return array
     */
    public function filterSchedulers(SchedulerFilterTransfer $schedulerFilterTransfer, array $allSchedulerKeys): array
    {
        $enabledSchedulers = $this->schedulerConfig->getEnabledSchedulers();

        if ($schedulerFilterTransfer->getSchedulers() === []) {
            return $enabledSchedulers;
        }

        $chosenSchedulers = $schedulerFilterTransfer === null ? $allSchedulerKeys : $schedulerFilterTransfer->getSchedulers();

        return array_intersect($chosenSchedulers, $enabledSchedulers, $allSchedulerKeys);
    }
}
