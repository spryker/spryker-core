<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\Clean;

use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerTransfer;
use Spryker\Zed\Scheduler\Business\ConfigurationReader\SchedulerConfigurationReaderInterface;

class SchedulerClean implements SchedulerCleanInterface
{
    /**
     * @var \Spryker\Zed\Scheduler\Business\ConfigurationReader\SchedulerConfigurationReaderInterface
     */
    protected $configurationReader;

    /**
     * @var \Spryker\Zed\SchedulerExtension\Dependency\Adapter\SchedulerAdapterPluginInterface[]
     */
    protected $schedulerAdapterPlugins;

    /**
     * @param \Spryker\Zed\Scheduler\Business\ConfigurationReader\SchedulerConfigurationReaderInterface $configurationReader
     * @param \Spryker\Zed\SchedulerExtension\Dependency\Adapter\SchedulerAdapterPluginInterface[] $schedulerAdapterPlugins
     */
    public function __construct(
        SchedulerConfigurationReaderInterface $configurationReader,
        array $schedulerAdapterPlugins
    ) {
        $this->configurationReader = $configurationReader;
        $this->schedulerAdapterPlugins = $schedulerAdapterPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function clean(SchedulerTransfer $schedulerTransfer): SchedulerResponseTransfer
    {
        $schedulerTransfer = $this->configurationReader->getCronJobsConfiguration($schedulerTransfer);

        $schedulerResponseTransfer = new SchedulerResponseTransfer();

        foreach ($this->schedulerAdapterPlugins as $schedulerId => $schedulerAdapterPlugin) {
            $schedulers = $schedulerTransfer->getSchedulers();
            if ($schedulers === [] || in_array($schedulerId, $schedulers)) {
                $schedulerResponseTransfer = $schedulerAdapterPlugin->clean($schedulerId, $schedulerTransfer, $schedulerResponseTransfer);
            }
        }

        return $schedulerResponseTransfer;
    }
}
