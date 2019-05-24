<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Scheduler\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SchedulerRequestTransfer;
use Generated\Shared\Transfer\SchedulerResponseCollectionTransfer;
use Generated\Shared\Transfer\SchedulerScheduleTransfer;
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
        $scheduleTransfer = $this->getSchedulerFacade()->readScheduleFromPhpSource(new SchedulerScheduleTransfer());

        $this->assertInstanceOf(SchedulerRequestTransfer::class, $scheduleTransfer);
    }

    /**
     * @return void
     */
    public function testSchedulerSetup(): void
    {
        $schedulerResponseCollectionTransfer = $this->getSchedulerFacade()->setup(new SchedulerRequestTransfer());

        $this->assertInstanceOf(SchedulerResponseCollectionTransfer::class, $schedulerResponseCollectionTransfer);
    }

    /**
     * @return void
     */
    public function testSchedulerClean(): void
    {
        $schedulerResponseCollectionTransfer = $this->getSchedulerFacade()->clean(new SchedulerRequestTransfer());

        $this->assertInstanceOf(SchedulerResponseCollectionTransfer::class, $schedulerResponseCollectionTransfer);
    }

    /**
     * @return void
     */
    public function testSchedulerResume(): void
    {
        $schedulerResponseCollectionTransfer = $this->getSchedulerFacade()->resume(new SchedulerRequestTransfer());

        $this->assertInstanceOf(SchedulerResponseCollectionTransfer::class, $schedulerResponseCollectionTransfer);
    }

    /**
     * @return void
     */
    public function testSchedulerSuspend(): void
    {
        $schedulerResponseCollectionTransfer = $this->getSchedulerFacade()->suspend(new SchedulerRequestTransfer());

        $this->assertInstanceOf(SchedulerResponseCollectionTransfer::class, $schedulerResponseCollectionTransfer);
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
            ->method('getSchedulerAdapterPlugins')
            ->willReturn([]);

        return $schedulerBusinessFactoryMock;
    }
}
