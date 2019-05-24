<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Scheduler\Business\Command\SchedulerCleanCommand;
use Spryker\Zed\Scheduler\Business\Command\SchedulerCommandInterface;
use Spryker\Zed\Scheduler\Business\Command\SchedulerResumeCommand;
use Spryker\Zed\Scheduler\Business\Command\SchedulerSetupCommand;
use Spryker\Zed\Scheduler\Business\Command\SchedulerSuspendCommand;
use Spryker\Zed\Scheduler\Business\PhpScheduleReader\PhpScheduleMapper;
use Spryker\Zed\Scheduler\Business\PhpScheduleReader\PhpScheduleMapperInterface;
use Spryker\Zed\Scheduler\Business\PhpScheduleReader\PhpScheduleReader;
use Spryker\Zed\Scheduler\Business\PhpScheduleReader\PhpScheduleReaderInterface;
use Spryker\Zed\Scheduler\Dependency\Store\SchedulerToStoreInterface;
use Spryker\Zed\Scheduler\SchedulerDependencyProvider;

/**
 * @method \Spryker\Zed\Scheduler\SchedulerConfig getConfig()
 */
class SchedulerBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Scheduler\Business\Command\SchedulerCommandInterface
     */
    public function createSchedulerSetup(): SchedulerCommandInterface
    {
        return new SchedulerSetupCommand(
            $this->getScheduleReaderPlugins(),
            $this->getSchedulerAdapterPlugins(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Scheduler\Business\Command\SchedulerCommandInterface
     */
    public function createSchedulerClean(): SchedulerCommandInterface
    {
        return new SchedulerCleanCommand(
            $this->getScheduleReaderPlugins(),
            $this->getSchedulerAdapterPlugins(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Scheduler\Business\Command\SchedulerCommandInterface
     */
    public function createSchedulerSuspend(): SchedulerCommandInterface
    {
        return new SchedulerSuspendCommand(
            $this->getScheduleReaderPlugins(),
            $this->getSchedulerAdapterPlugins(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Scheduler\Business\Command\SchedulerCommandInterface
     */
    public function createSchedulerResume(): SchedulerCommandInterface
    {
        return new SchedulerResumeCommand(
            $this->getScheduleReaderPlugins(),
            $this->getSchedulerAdapterPlugins(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Scheduler\Business\PhpScheduleReader\PhpScheduleReaderInterface
     */
    public function createPhpSchedulerReader(): PhpScheduleReaderInterface
    {
        return new PhpScheduleReader(
            $this->createPhpSchedulerMapper(),
            $this->getStore(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Scheduler\Business\PhpScheduleReader\PhpScheduleMapperInterface
     */
    public function createPhpSchedulerMapper(): PhpScheduleMapperInterface
    {
        return new PhpScheduleMapper();
    }

    /**
     * @return \Spryker\Zed\SchedulerExtension\Dependency\Plugin\ScheduleReaderPluginInterface[]
     */
    public function getScheduleReaderPlugins(): array
    {
        return $this->getProvidedDependency(SchedulerDependencyProvider::PLUGINS_SCHEDULE_READER);
    }

    /**
     * @return \Spryker\Zed\SchedulerExtension\Dependency\Plugin\SchedulerAdapterPluginInterface[]
     */
    public function getSchedulerAdapterPlugins(): array
    {
        return $this->getProvidedDependency(SchedulerDependencyProvider::PLUGINS_SCHEDULER_ADAPTER);
    }

    /**
     * @return \Spryker\Zed\Scheduler\Dependency\Store\SchedulerToStoreInterface
     */
    public function getStore(): SchedulerToStoreInterface
    {
        return $this->getProvidedDependency(SchedulerDependencyProvider::STORE);
    }
}
