<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Scheduler\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerTransfer;
use Spryker\Zed\Scheduler\Business\Executor\SchedulerAdapterPluginsExecutor;
use Spryker\Zed\Scheduler\Business\SchedulerBusinessFactory;
use Spryker\Zed\Scheduler\Business\SchedulerFacade;
use Spryker\Zed\Scheduler\Business\SchedulerFacadeInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Scheduler
 * @group Business
 * @group Facade
 * @group SchedulerFacadeTest
 * Add your own group annotations below this line
 */
class SchedulerFacadeTest extends Unit
{
    /**
     * @return void
     */
    public function testSchedulerConfigurationReaderWithoutReaders(): void
    {
        $schedulerTransfer = $this->getSchedulerFacade()->getPhpCronJobsConfiguration(new SchedulerTransfer());

        $this->assertInstanceOf(SchedulerTransfer::class, $schedulerTransfer);
    }

    /**
     * @return void
     */
    public function testSchedulerSetup(): void
    {
        $schedulerResponseTransfer = $this->getSchedulerFacade()->setup(new SchedulerTransfer());

        $this->assertInstanceOf(SchedulerResponseTransfer::class, $schedulerResponseTransfer);
    }

    /**
     * @return void
     */
    public function testSchedulerClean(): void
    {
        $schedulerResponseTransfer = $this->getSchedulerFacade()->clean(new SchedulerTransfer());

        $this->assertInstanceOf(SchedulerResponseTransfer::class, $schedulerResponseTransfer);
    }

    /**
     * @return void
     */
    public function testSchedulerResume(): void
    {
        $schedulerResponseTransfer = $this->getSchedulerFacade()->resume(new SchedulerTransfer());

        $this->assertInstanceOf(SchedulerResponseTransfer::class, $schedulerResponseTransfer);
    }

    /**
     * @return void
     */
    public function testSchedulerSuspend(): void
    {
        $schedulerResponseTransfer = $this->getSchedulerFacade()->suspend(new SchedulerTransfer());

        $this->assertInstanceOf(SchedulerResponseTransfer::class, $schedulerResponseTransfer);
    }

    /**
     * @return \Spryker\Zed\Scheduler\Business\SchedulerFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getSchedulerFacade(): SchedulerFacadeInterface
    {
        return (new SchedulerFacade())
            ->setFactory($this->getSchedulerBusinessFactoryMock());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Scheduler\Business\SchedulerBusinessFactory
     */
    protected function getSchedulerBusinessFactoryMock()
    {
        $schedulerBusinessFactoryMock = $this->getMockBuilder(SchedulerBusinessFactory::class)
            ->getMock();

        $schedulerBusinessFactoryMock
            ->method('createSchedulerAdapterPluginsExecutor')
            ->willReturn($this->getSchedulerAdapterPluginsExecutorMock());

        return $schedulerBusinessFactoryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Scheduler\Business\Executor\SchedulerAdapterPluginsExecutorInterface
     */
    protected function getSchedulerAdapterPluginsExecutorMock()
    {
        $schedulerAdapterPluginsExecutorMock = $this->getMockBuilder(SchedulerAdapterPluginsExecutor::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'executeSchedulerAdapterPluginsForSchedulerSetup',
                'executeSchedulerAdapterPluginsForSchedulerClean',
                'executeSchedulerAdapterPluginsForSchedulerResume',
                'executeSchedulerAdapterPluginsForSchedulerSuspend',
            ])
            ->getMock();

        $schedulerAdapterPluginsExecutorMock
            ->expects($this->any())
            ->method('executeSchedulerAdapterPluginsForSchedulerSetup')
            ->willReturn(new SchedulerResponseTransfer());

        $schedulerAdapterPluginsExecutorMock
            ->expects($this->any())
            ->method('executeSchedulerAdapterPluginsForSchedulerClean')
            ->willReturn(new SchedulerResponseTransfer());

        $schedulerAdapterPluginsExecutorMock
            ->expects($this->any())
            ->method('executeSchedulerAdapterPluginsForSchedulerResume')
            ->willReturn(new SchedulerResponseTransfer());

        $schedulerAdapterPluginsExecutorMock
            ->expects($this->any())
            ->method('executeSchedulerAdapterPluginsForSchedulerSuspend')
            ->willReturn(new SchedulerResponseTransfer());

        return $schedulerAdapterPluginsExecutorMock;
    }
}
