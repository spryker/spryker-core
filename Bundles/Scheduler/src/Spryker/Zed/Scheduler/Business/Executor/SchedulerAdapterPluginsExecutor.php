<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\Executor;

use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerTransfer;

class SchedulerAdapterPluginsExecutor implements SchedulerAdapterPluginsExecutorInterface
{
    /**
     * @var \Spryker\Zed\SchedulerExtension\Dependency\Plugin\SchedulerReaderPluginInterface[]
     */
    protected $schedulerReaderPlugins;

    /**
     * @var \Spryker\Zed\SchedulerExtension\Dependency\Adapter\SchedulerAdapterPluginInterface[]
     */
    protected $schedulerAdapterPlugins;

    /**
     * @param \Spryker\Zed\SchedulerExtension\Dependency\Plugin\SchedulerReaderPluginInterface[] $schedulerReaderPlugins
     * @param \Spryker\Zed\SchedulerExtension\Dependency\Adapter\SchedulerAdapterPluginInterface[] $schedulerAdapterPlugins
     */
    public function __construct(array $schedulerReaderPlugins, array $schedulerAdapterPlugins)
    {
        $this->schedulerReaderPlugins = $schedulerReaderPlugins;
        $this->schedulerAdapterPlugins = $schedulerAdapterPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerTransfer
     */
    public function executeSchedulerReaderPlugins(SchedulerTransfer $schedulerTransfer): SchedulerTransfer
    {
        foreach ($this->schedulerReaderPlugins as $schedulerReaderPlugin) {
            $schedulerTransfer = $schedulerReaderPlugin->readSchedule($schedulerTransfer);
        }

        return $schedulerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function executeSchedulerAdapterPluginsForSchedulerSetup(SchedulerTransfer $schedulerTransfer): SchedulerResponseTransfer
    {
        $schedulerResponseTransfer = $this->createSchedulerResponseTransfer();

        foreach ($this->schedulerAdapterPlugins as $schedulerId => $schedulerAdapterPlugin) {
            if ($schedulerAdapterPlugin->isApplicable($schedulerId, $schedulerTransfer)) {
                $schedulerResponseTransfer = $schedulerAdapterPlugin->setup($schedulerId, $schedulerTransfer, $schedulerResponseTransfer);
            }
        }

        return $schedulerResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function executeSchedulerAdapterPluginsForSchedulerClean(SchedulerTransfer $schedulerTransfer): SchedulerResponseTransfer
    {
        $schedulerResponseTransfer = $this->createSchedulerResponseTransfer();

        foreach ($this->schedulerAdapterPlugins as $schedulerId => $schedulerAdapterPlugin) {
            if ($schedulerAdapterPlugin->isApplicable($schedulerId, $schedulerTransfer)) {
                $schedulerResponseTransfer = $schedulerAdapterPlugin->clean($schedulerId, $schedulerTransfer, $schedulerResponseTransfer);
            }
        }

        return $schedulerResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function executeSchedulerAdapterPluginsForSchedulerResume(SchedulerTransfer $schedulerTransfer): SchedulerResponseTransfer
    {
        $schedulerResponseTransfer = $this->createSchedulerResponseTransfer();

        foreach ($this->schedulerAdapterPlugins as $schedulerId => $schedulerAdapterPlugin) {
            if ($schedulerAdapterPlugin->isApplicable($schedulerId, $schedulerTransfer)) {
                $schedulerResponseTransfer = $schedulerAdapterPlugin->resume($schedulerId, $schedulerTransfer, $schedulerResponseTransfer);
            }
        }

        return $schedulerResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function executeSchedulerAdapterPluginsForSchedulerSuspend(SchedulerTransfer $schedulerTransfer): SchedulerResponseTransfer
    {
        $schedulerResponseTransfer = $this->createSchedulerResponseTransfer();

        foreach ($this->schedulerAdapterPlugins as $schedulerId => $schedulerAdapterPlugin) {
            if ($schedulerAdapterPlugin->isApplicable($schedulerId, $schedulerTransfer)) {
                $schedulerResponseTransfer = $schedulerAdapterPlugin->suspend($schedulerId, $schedulerTransfer, $schedulerResponseTransfer);
            }
        }

        return $schedulerResponseTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    protected function createSchedulerResponseTransfer(): SchedulerResponseTransfer
    {
        return new SchedulerResponseTransfer();
    }
}
