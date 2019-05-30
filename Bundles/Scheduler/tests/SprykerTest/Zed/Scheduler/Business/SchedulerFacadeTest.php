<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Scheduler\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SchedulerFilterTransfer;
use Generated\Shared\Transfer\SchedulerJobTransfer;
use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerScheduleTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Scheduler\Business\Command\Filter\SchedulerFilter;
use Spryker\Zed\Scheduler\Business\Command\Filter\SchedulerFilterInterface;
use Spryker\Zed\Scheduler\Business\Command\SchedulerSetupCommand;
use Spryker\Zed\Scheduler\Business\PhpScheduleReader\Filter\JobsFilterByName;
use Spryker\Zed\Scheduler\Business\PhpScheduleReader\Mapper\PhpScheduleMapper;
use Spryker\Zed\Scheduler\Business\PhpScheduleReader\Mapper\PhpScheduleMapperInterface;
use Spryker\Zed\Scheduler\Business\PhpScheduleReader\PhpScheduleReader;
use Spryker\Zed\Scheduler\Business\PhpScheduleReader\PhpScheduleReaderInterface;
use Spryker\Zed\Scheduler\Business\SchedulerBusinessFactory;
use Spryker\Zed\Scheduler\Business\SchedulerFacade;
use Spryker\Zed\Scheduler\Business\SchedulerFacadeInterface;
use Spryker\Zed\Scheduler\Communication\Plugin\Scheduler\PhpScheduleReaderPlugin;
use Spryker\Zed\Scheduler\Dependency\Store\SchedulerToStoreBridge;
use Spryker\Zed\Scheduler\SchedulerConfig;
use Spryker\Zed\SchedulerExtension\Dependency\Plugin\SchedulerAdapterPluginInterface;

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
    protected const TEST_SCHEDULER = 'test';

    /**
     * @return void
     */
    public function testSchedulerConfigurationReaderWithoutReadersForTheCurrentStore(): void
    {
        $filterTransfer = $this->createSchedulerFilterTransfer();
        $scheduleTransfer = $this->createSchedulerSchedulerTransfer();
        $scheduleTransfer = $this->getSchedulerFacade()->readScheduleFromPhpSource($filterTransfer, $scheduleTransfer);

        $this->assertInstanceOf(SchedulerScheduleTransfer::class, $scheduleTransfer);
        $this->assertSame(2, $scheduleTransfer->getJobs()->count());

        foreach ($scheduleTransfer->getJobs() as $jobTransfer) {
            $this->assertContains(APPLICATION_STORE, $jobTransfer->getName());
        }
    }

    /**
     * @return void
     */
    public function testSchedulerSetup(): void
    {
        $filterTransfer = $this->createSchedulerFilterTransfer();
        $responseCollectionTransfer = $this->getSchedulerFacade()->setup($filterTransfer);

        $this->assertNotEmpty($responseCollectionTransfer->getResponses());

        foreach ($responseCollectionTransfer->getResponses() as $responseTransfer) {
            $this->assertEquals(static::TEST_SCHEDULER, $responseTransfer->getSchedule()->getIdScheduler());
        }
    }

    /**
     * @return void
     */
    public function testSchedulerClean(): void
    {
        $filterTransfer = $this->createSchedulerFilterTransfer();
        $responseCollectionTransfer = $this->getSchedulerFacade()->clean($filterTransfer);

        $this->assertNotEmpty($responseCollectionTransfer->getResponses());

        foreach ($responseCollectionTransfer->getResponses() as $responseTransfer) {
            $this->assertEquals(static::TEST_SCHEDULER, $responseTransfer->getSchedule()->getIdScheduler());
        }
    }

    /**
     * @return void
     */
    public function testSchedulerResume(): void
    {
        $filterTransfer = $this->createSchedulerFilterTransfer();
        $responseCollectionTransfer = $this->getSchedulerFacade()->resume($filterTransfer);

        $this->assertNotEmpty($responseCollectionTransfer->getResponses());

        foreach ($responseCollectionTransfer->getResponses() as $responseTransfer) {
            $this->assertEquals(static::TEST_SCHEDULER, $responseTransfer->getSchedule()->getIdScheduler());
        }
    }

    /**
     * @return void
     */
    public function testSchedulerSuspend(): void
    {
        $requestTransfer = $this->createSchedulerFilterTransfer();
        $responseCollectionTransfer = $this->getSchedulerFacade()->suspend($requestTransfer);

        $this->assertNotEmpty($responseCollectionTransfer->getResponses());

        foreach ($responseCollectionTransfer->getResponses() as $responseTransfer) {
            $this->assertEquals(static::TEST_SCHEDULER, $responseTransfer->getSchedule()->getIdScheduler());
        }
    }

    /**
     * @return \Spryker\Zed\Scheduler\Business\SchedulerFacadeInterface
     */
    protected function getSchedulerFacade(): SchedulerFacadeInterface
    {
        return (new SchedulerFacade())
            ->setFactory($this->getSchedulerBusinessFactoryMock());
    }

    /**
     * @return \Generated\Shared\Transfer\SchedulerFilterTransfer
     */
    protected function createSchedulerFilterTransfer(): SchedulerFilterTransfer
    {
        return (new SchedulerFilterTransfer())
                ->setSchedulers([static::TEST_SCHEDULER]);
    }

    /**
     * @return \Generated\Shared\Transfer\SchedulerScheduleTransfer
     */
    protected function createSchedulerSchedulerTransfer(): SchedulerScheduleTransfer
    {
        return (new SchedulerScheduleTransfer())
            ->setIdScheduler(static::TEST_SCHEDULER);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Scheduler\Business\SchedulerBusinessFactory
     */
    protected function getSchedulerBusinessFactoryMock()
    {
        $schedulerBusinessFactoryMock = $this->getMockBuilder(SchedulerBusinessFactory::class)
            ->setMethods([
                'getSchedulerAdapterPlugins',
                'getScheduleReaderPlugins',
                'getConfig',
                'createPhpSchedulerMapper',
                'createSchedulerFilter',
                'createSchedulerSetup',
            ])
            ->getMock();

        $schedulerBusinessFactoryMock
            ->method('getSchedulerAdapterPlugins')
            ->willReturn($this->getSchedulerAdapterPlugins());

        $schedulerBusinessFactoryMock
            ->method('getScheduleReaderPlugins')
            ->willReturn($this->getSchedulerReaderPlugins());

        $schedulerBusinessFactoryMock
            ->method('createPhpSchedulerMapper')
            ->willReturn($this->createPhpSchedulerMapperInstance());

        $schedulerBusinessFactoryMock
            ->method('getConfig')
            ->willReturn($this->getSchedulerConfigMock());

        $schedulerBusinessFactoryMock
            ->method('createSchedulerFilter')
            ->willReturn($this->getSchedulerFilter());

        $schedulerBusinessFactoryMock
            ->method('createSchedulerSetup')
            ->willReturn(
                new SchedulerSetupCommand(
                    $this->getSchedulerReaderPlugins(),
                    $this->getSchedulerAdapterPlugins(),
                    $this->getSchedulerFilter()
                )
            );

        return $schedulerBusinessFactoryMock;
    }

    /**
     * @return \Spryker\Zed\Scheduler\Business\PhpScheduleReader\Mapper\PhpScheduleMapperInterface
     */
    protected function createPhpSchedulerMapperInstance(): PhpScheduleMapperInterface
    {
        return new PhpScheduleMapper(
            new JobsFilterByName(),
            new SchedulerToStoreBridge(Store::getInstance())
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Scheduler\SchedulerConfig
     */
    protected function getSchedulerConfigMock()
    {
        $schedulerConfigMock = $this->getMockBuilder(SchedulerConfig::class)
            ->setMethods([
                'getPhpSchedulerReaderPath',
                'getEnabledSchedulers',
            ])
            ->getMock();

        $schedulerConfigMock
            ->method('getPhpSchedulerReaderPath')
            ->willReturn($this->getPhpSchedulerReaderPath());

        $schedulerConfigMock
            ->method('getEnabledSchedulers')
            ->willReturn($this->getEnabledSchedulers());

        return $schedulerConfigMock;
    }

    /**
     * @return \Spryker\Zed\SchedulerExtension\Dependency\Plugin\ScheduleReaderPluginInterface[]
     */
    protected function getSchedulerReaderPlugins(): array
    {
        return [
            $this->getPhpSchedulerReaderPluginMock(),
        ];
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SchedulerExtension\Dependency\Plugin\ScheduleReaderPluginInterface
     */
    protected function getPhpSchedulerReaderPluginMock()
    {
        $phpSchedulerReaderPluginMock = $this->getMockBuilder(PhpScheduleReaderPlugin::class)
            ->setMethods(['readSchedule'])
            ->getMock();

        $phpSchedulerReaderPluginMock
            ->method('readSchedule')
            ->willReturn($this->createSchedulerTransfer());

        return $phpSchedulerReaderPluginMock;
    }

    /**
     * @return \Generated\Shared\Transfer\SchedulerScheduleTransfer
     */
    protected function createSchedulerTransfer(): SchedulerScheduleTransfer
    {
        return (new SchedulerScheduleTransfer())
            ->addJob((new SchedulerJobTransfer())->setName('DE_test')->setStore('DE'))
            ->addJob((new SchedulerJobTransfer())->setName('DE_test1')->setStore('DE'));
    }

    /**
     * @return \Spryker\Zed\Scheduler\Business\Command\Filter\SchedulerFilterInterface
     */
    protected function getSchedulerFilter(): SchedulerFilterInterface
    {
        return new SchedulerFilter(
            $this->getSchedulerConfigMock()
        );
    }

    /**
     * @return \Spryker\Zed\Scheduler\Business\PhpScheduleReader\PhpScheduleReaderInterface
     */
    protected function getPhpSchedulerReader(): PhpScheduleReaderInterface
    {
        return new PhpScheduleReader(
            $this->createPhpSchedulerMapperInstance(),
            $this->getSchedulerConfigMock()
        );
    }

    /**
     * @return array
     */
    protected function getSchedulerAdapterPlugins(): array
    {
        return [
            'test' => $this->getSchedulerAdapterPluginMock(),
            'test1' => 'test1',
        ];
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SchedulerExtension\Dependency\Plugin\SchedulerAdapterPluginInterface
     */
    protected function getSchedulerAdapterPluginMock()
    {
        $schedulerAdapterPluginMock = $this->getMockBuilder(SchedulerAdapterPluginInterface::class)
            ->setMethods([
                'setup',
                'clean',
                'suspend',
                'resume',
            ])
            ->getMock();

        $schedulerAdapterPluginMock
            ->method('setup')
            ->willReturn($this->createSchedulerResponseTransfer());

        $schedulerAdapterPluginMock
            ->method('clean')
            ->willReturn($this->createSchedulerResponseTransfer());

        $schedulerAdapterPluginMock
            ->method('suspend')
            ->willReturn($this->createSchedulerResponseTransfer());

        $schedulerAdapterPluginMock
            ->method('resume')
            ->willReturn($this->createSchedulerResponseTransfer());

        return $schedulerAdapterPluginMock;
    }

    /**
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    protected function createSchedulerResponseTransfer(): SchedulerResponseTransfer
    {
        return (new SchedulerResponseTransfer())
            ->setSchedule($this->createSchedulerSchedulerTransfer());
    }

    /**
     * @return string
     */
    protected function getPhpSchedulerReaderPath(): string
    {
        return codecept_data_dir() . static::TEST_SCHEDULER . '.php';
    }

    /**
     * @return string[]
     */
    protected function getEnabledSchedulers(): array
    {
        return [
            static::TEST_SCHEDULER,
        ];
    }
}
