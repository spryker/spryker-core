<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Scheduler\Business\Command\Filter\SchedulerFilter;
use Spryker\Zed\Scheduler\Business\Command\Filter\SchedulerFilterInterface;
use Spryker\Zed\Scheduler\Business\Command\SchedulerCleanCommand;
use Spryker\Zed\Scheduler\Business\Command\SchedulerCommandInterface;
use Spryker\Zed\Scheduler\Business\Command\SchedulerResumeCommand;
use Spryker\Zed\Scheduler\Business\Command\SchedulerSetupCommand;
use Spryker\Zed\Scheduler\Business\Command\SchedulerSuspendCommand;
use Spryker\Zed\Scheduler\Business\PhpScheduleReader\Filter\ChainableJobsFilterInterface;
use Spryker\Zed\Scheduler\Business\PhpScheduleReader\Filter\JobsFilterByName;
use Spryker\Zed\Scheduler\Business\PhpScheduleReader\Filter\JobsFilterByRole;
use Spryker\Zed\Scheduler\Business\PhpScheduleReader\Filter\JobsFilterByStore;
use Spryker\Zed\Scheduler\Business\PhpScheduleReader\Filter\JobsFilterInterface;
use Spryker\Zed\Scheduler\Business\PhpScheduleReader\Mapper\PhpScheduleMapper;
use Spryker\Zed\Scheduler\Business\PhpScheduleReader\Mapper\PhpScheduleMapperInterface;
use Spryker\Zed\Scheduler\Business\PhpScheduleReader\PhpScheduleReader;
use Spryker\Zed\Scheduler\Business\PhpScheduleReader\PhpScheduleReaderInterface;
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
            $this->createSchedulerFilter()
        );
    }

    /**
     * @return \Spryker\Zed\Scheduler\Business\Command\SchedulerCommandInterface
     */
    public function createSchedulerClean(): SchedulerCommandInterface
    {
        return new SchedulerCleanCommand(
            $this->getScheduleReaderPlugins(),
            $this->createSchedulerFilter()
        );
    }

    /**
     * @return \Spryker\Zed\Scheduler\Business\Command\SchedulerCommandInterface
     */
    public function createSchedulerSuspend(): SchedulerCommandInterface
    {
        return new SchedulerSuspendCommand(
            $this->getScheduleReaderPlugins(),
            $this->createSchedulerFilter()
        );
    }

    /**
     * @return \Spryker\Zed\Scheduler\Business\Command\SchedulerCommandInterface
     */
    public function createSchedulerResume(): SchedulerCommandInterface
    {
        return new SchedulerResumeCommand(
            $this->getScheduleReaderPlugins(),
            $this->createSchedulerFilter()
        );
    }

    /**
     * @return \Spryker\Zed\Scheduler\Business\PhpScheduleReader\PhpScheduleReaderInterface
     */
    public function createPhpSchedulerReader(): PhpScheduleReaderInterface
    {
        return new PhpScheduleReader(
            $this->createPhpSchedulerMapper(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Scheduler\Business\PhpScheduleReader\Mapper\PhpScheduleMapperInterface
     */
    public function createPhpSchedulerMapper(): PhpScheduleMapperInterface
    {
        return new PhpScheduleMapper(
            $this->createJobsFilter()
        );
    }

    /**
     * @return \Spryker\Zed\Scheduler\Business\PhpScheduleReader\Filter\JobsFilterInterface
     */
    public function createJobsFilter(): JobsFilterInterface
    {
        $jobsFilterByName = $this->createJobsFilterByName();
        $jobsFilterByStore = $this->createJobsFilterByStore();
        $jobsFilterByRole = $this->createJobsFilterByRole();

        return $jobsFilterByName->setNextFilter(
            $jobsFilterByStore->setNextFilter(
                $jobsFilterByRole
            )
        );
    }

    /**
     * @return \Spryker\Zed\Scheduler\Business\PhpScheduleReader\Filter\ChainableJobsFilterInterface
     */
    public function createJobsFilterByName(): ChainableJobsFilterInterface
    {
        return new JobsFilterByName();
    }

    /**
     * @return \Spryker\Zed\Scheduler\Business\PhpScheduleReader\Filter\ChainableJobsFilterInterface
     */
    public function createJobsFilterByStore(): ChainableJobsFilterInterface
    {
        return new JobsFilterByStore();
    }

    /**
     * @return \Spryker\Zed\Scheduler\Business\PhpScheduleReader\Filter\ChainableJobsFilterInterface
     */
    public function createJobsFilterByRole(): ChainableJobsFilterInterface
    {
        return new JobsFilterByRole(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Scheduler\Business\Command\Filter\SchedulerFilterInterface
     */
    public function createSchedulerFilter(): SchedulerFilterInterface
    {
        return new SchedulerFilter(
            $this->getConfig(),
            $this->getSchedulerAdapterPlugins()
        );
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
}
