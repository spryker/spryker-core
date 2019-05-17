<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Scheduler\Business\Clean\SchedulerClean;
use Spryker\Zed\Scheduler\Business\Clean\SchedulerCleanInterface;
use Spryker\Zed\Scheduler\Business\ConfigurationReader\PhpConfigurationReader\PhpSchedulerReader;
use Spryker\Zed\Scheduler\Business\ConfigurationReader\PhpConfigurationReader\PhpSchedulerReaderInterface;
use Spryker\Zed\Scheduler\Business\ConfigurationReader\SchedulerConfigurationReader;
use Spryker\Zed\Scheduler\Business\ConfigurationReader\SchedulerConfigurationReaderInterface;
use Spryker\Zed\Scheduler\Business\Executor\SchedulerAdapterPluginsExecutor;
use Spryker\Zed\Scheduler\Business\Executor\SchedulerAdapterPluginsExecutorInterface;
use Spryker\Zed\Scheduler\Business\Resume\SchedulerResume;
use Spryker\Zed\Scheduler\Business\Resume\SchedulerResumeInterface;
use Spryker\Zed\Scheduler\Business\Setup\SchedulerSetup;
use Spryker\Zed\Scheduler\Business\Setup\SchedulerSetupInterface;
use Spryker\Zed\Scheduler\Business\Suspend\SchedulerSuspend;
use Spryker\Zed\Scheduler\Business\Suspend\SchedulerSuspendInterface;
use Spryker\Zed\Scheduler\Dependency\Store\SchedulerToStoreInterface;
use Spryker\Zed\Scheduler\SchedulerDependencyProvider;

/**
 * @method \Spryker\Zed\Scheduler\SchedulerConfig getConfig()
 */
class SchedulerBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Scheduler\Business\Setup\SchedulerSetupInterface
     */
    public function createSchedulerSetup(): SchedulerSetupInterface
    {
        return new SchedulerSetup(
            $this->createConfigurationReader(),
            $this->createSchedulerAdapterPluginsExecutor()
        );
    }

    /**
     * @return \Spryker\Zed\Scheduler\Business\Clean\SchedulerCleanInterface
     */
    public function createSchedulerClean(): SchedulerCleanInterface
    {
        return new SchedulerClean(
            $this->createConfigurationReader(),
            $this->createSchedulerAdapterPluginsExecutor()
        );
    }

    /**
     * @return \Spryker\Zed\Scheduler\Business\Suspend\SchedulerSuspendInterface
     */
    public function createSchedulerSuspend(): SchedulerSuspendInterface
    {
        return new SchedulerSuspend(
            $this->createConfigurationReader(),
            $this->createSchedulerAdapterPluginsExecutor()
        );
    }

    /**
     * @return \Spryker\Zed\Scheduler\Business\Resume\SchedulerResumeInterface
     */
    public function createSchedulerResume(): SchedulerResumeInterface
    {
        return new SchedulerResume(
            $this->createConfigurationReader(),
            $this->createSchedulerAdapterPluginsExecutor()
        );
    }

    /**
     * @return \Spryker\Zed\Scheduler\Business\ConfigurationReader\PhpConfigurationReader\PhpSchedulerReaderInterface
     */
    public function createPhpSchedulerReader(): PhpSchedulerReaderInterface
    {
        return new PhpSchedulerReader(
            $this->getStore(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Scheduler\Business\ConfigurationReader\SchedulerConfigurationReaderInterface
     */
    public function createConfigurationReader(): SchedulerConfigurationReaderInterface
    {
        return new SchedulerConfigurationReader(
            $this->createSchedulerAdapterPluginsExecutor()
        );
    }

    /**
     * @return \Spryker\Zed\Scheduler\Business\Executor\SchedulerAdapterPluginsExecutorInterface
     */
    public function createSchedulerAdapterPluginsExecutor(): SchedulerAdapterPluginsExecutorInterface
    {
        return new SchedulerAdapterPluginsExecutor(
            $this->getSchedulerConfigurationReaderPlugins(),
            $this->getSchedulerAdapterPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\SchedulerExtension\Dependency\Plugin\SchedulerReaderPluginInterface[]
     */
    public function getSchedulerConfigurationReaderPlugins(): array
    {
        return $this->getProvidedDependency(SchedulerDependencyProvider::SCHEDULER_READER_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\SchedulerExtension\Dependency\Adapter\SchedulerAdapterPluginInterface[]
     */
    public function getSchedulerAdapterPlugins(): array
    {
        return $this->getProvidedDependency(SchedulerDependencyProvider::SCHEDULER_ADAPTER_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Scheduler\Dependency\Store\SchedulerToStoreInterface
     */
    public function getStore(): SchedulerToStoreInterface
    {
        return $this->getProvidedDependency(SchedulerDependencyProvider::STORE);
    }
}
