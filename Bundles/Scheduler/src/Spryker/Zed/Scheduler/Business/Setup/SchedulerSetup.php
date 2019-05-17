<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\Setup;

use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerTransfer;
use Spryker\Zed\Scheduler\Business\ConfigurationReader\SchedulerConfigurationReaderInterface;
use Spryker\Zed\Scheduler\Business\Executor\SchedulerAdapterPluginsExecutorInterface;

class SchedulerSetup implements SchedulerSetupInterface
{
    /**
     * @var \Spryker\Zed\Scheduler\Business\ConfigurationReader\SchedulerConfigurationReaderInterface
     */
    protected $configurationReader;

    /**
     * @var \Spryker\Zed\Scheduler\Business\Executor\SchedulerAdapterPluginsExecutorInterface
     */
    protected $schedulerAdapterPluginsExecutor;

    /**
     * @param \Spryker\Zed\Scheduler\Business\ConfigurationReader\SchedulerConfigurationReaderInterface $configurationReader
     * @param \Spryker\Zed\Scheduler\Business\Executor\SchedulerAdapterPluginsExecutorInterface $schedulerAdapterPluginExecutor
     */
    public function __construct(
        SchedulerConfigurationReaderInterface $configurationReader,
        SchedulerAdapterPluginsExecutorInterface $schedulerAdapterPluginExecutor
    ) {
        $this->configurationReader = $configurationReader;
        $this->schedulerAdapterPluginsExecutor = $schedulerAdapterPluginExecutor;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function setup(SchedulerTransfer $schedulerTransfer): SchedulerResponseTransfer
    {
        $schedulerTransfer = $this->configurationReader->getCronJobsConfiguration($schedulerTransfer);
        $schedulerResponseTransfer = $this->schedulerAdapterPluginsExecutor->executeSchedulerAdapterPluginsForSchedulerSetup($schedulerTransfer);

        return $schedulerResponseTransfer;
    }
}
