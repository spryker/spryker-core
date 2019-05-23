<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\ConfigurationReader;

use Generated\Shared\Transfer\SchedulerTransfer;

class SchedulerConfigurationReader implements SchedulerConfigurationReaderInterface
{
    /**
     * @var \Spryker\Zed\SchedulerExtension\Dependency\Plugin\SchedulerReaderPluginInterface[]
     */
    protected $schedulerConfigurationReaderPlugins;

    /**
     * @param \Spryker\Zed\SchedulerExtension\Dependency\Plugin\SchedulerReaderPluginInterface[] $schedulerConfigurationReaderPlugins
     */
    public function __construct(
        array $schedulerConfigurationReaderPlugins
    ) {
        $this->schedulerConfigurationReaderPlugins = $schedulerConfigurationReaderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerTransfer
     */
    public function getCronJobsConfiguration(SchedulerTransfer $schedulerTransfer): SchedulerTransfer
    {
        foreach ($this->schedulerConfigurationReaderPlugins as $schedulerReaderPlugin) {
            $schedulerTransfer = $schedulerReaderPlugin->readSchedule($schedulerTransfer);
        }

        return $schedulerTransfer;
    }
}
