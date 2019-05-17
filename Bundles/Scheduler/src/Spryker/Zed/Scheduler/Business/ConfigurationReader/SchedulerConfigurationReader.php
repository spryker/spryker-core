<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\ConfigurationReader;

use Generated\Shared\Transfer\SchedulerTransfer;
use Spryker\Zed\Scheduler\Business\Executor\SchedulerAdapterPluginsExecutorInterface;

class SchedulerConfigurationReader implements SchedulerConfigurationReaderInterface
{
    /**
     * @var \Spryker\Zed\Scheduler\Business\Executor\SchedulerAdapterPluginsExecutorInterface
     */
    protected $schedulerAdapterPluginsExecutor;

    /**
     * @param \Spryker\Zed\Scheduler\Business\Executor\SchedulerAdapterPluginsExecutorInterface $schedulerAdapterPluginExecutor
     */
    public function __construct(
        SchedulerAdapterPluginsExecutorInterface $schedulerAdapterPluginExecutor
    ) {
        $this->schedulerAdapterPluginsExecutor = $schedulerAdapterPluginExecutor;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerTransfer
     */
    public function getCronJobsConfiguration(SchedulerTransfer $schedulerTransfer): SchedulerTransfer
    {
        $schedulerTransfer = $this->schedulerAdapterPluginsExecutor->executeSchedulerReaderPlugins($schedulerTransfer);

        return $schedulerTransfer;
    }
}
