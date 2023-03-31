<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Scheduler\Business;

use Codeception\Test\Unit;
use Exception;
use Generated\Shared\Transfer\SchedulerFilterTransfer;
use Generated\Shared\Transfer\SchedulerJobTransfer;
use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerScheduleTransfer;
use Spryker\Zed\Scheduler\Business\Command\Filter\SchedulerFilter;
use Spryker\Zed\Scheduler\Business\Command\Filter\SchedulerFilterInterface;
use Spryker\Zed\Scheduler\Business\SchedulerFacadeInterface;
use Spryker\Zed\Scheduler\Communication\Plugin\Scheduler\PhpScheduleReaderPlugin;
use Spryker\Zed\Scheduler\SchedulerConfig;
use Spryker\Zed\SchedulerExtension\Dependency\Plugin\SchedulerAdapterPluginInterface;
use Spryker\Zed\SchedulerExtension\Dependency\Plugin\ScheduleReaderPluginInterface;

/**
 * Auto-generated group annotations
 *
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
     * @var string
     */
    protected const TEST_SCHEDULER = 'test';

    /**
     * @var string
     */
    protected const TEST_FAILING_SCHEDULER = 'test-failing';

    /**
     * @var string
     */
    protected const STORE_NAME = 'DE';

    /**
     * @var string
     */
    protected const ROLE_ADMIN = 'admin';

    /**
     * @var string
     */
    protected const EXCEPTION_MESSAGE = 'test-exception-message';

    /**
     * @var string
     */
    protected const SCHEDULER_METHOD_SETUP = 'setup';

    /**
     * @var string
     */
    protected const SCHEDULER_METHOD_CLEAN = 'clean';

    /**
     * @var string
     */
    protected const SCHEDULER_METHOD_SUSPEND = 'suspend';

    /**
     * @var string
     */
    protected const SCHEDULER_METHOD_RESUME = 'resume';

    /**
     * @var \SprykerTest\Zed\Scheduler\SchedulerBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSchedulerConfigurationReaderWithoutReadersForTheCurrentStore(): void
    {
        //Arrange
        $filterTransfer = $this->createSchedulerFilterTransfer();
        $scheduleTransfer = $this->createSchedulerSchedulerTransfer();

        //Act
        $scheduleTransfer = $this->getSchedulerFacade()->readScheduleFromPhpSource($filterTransfer, $scheduleTransfer);

        //Assert
        $this->assertInstanceOf(SchedulerScheduleTransfer::class, $scheduleTransfer);
        $this->assertSame(2, $scheduleTransfer->getJobs()->count());

        foreach ($scheduleTransfer->getJobs() as $jobTransfer) {
            $storeName = $jobTransfer->getStore();

            //Empty store for job means that job is created for a whole region.
            if ($storeName === null) {
                continue;
            }
            $this->assertStringContainsString(static::STORE_NAME, $storeName);
        }
    }

    /**
     * @return void
     */
    public function testSchedulerSetup(): void
    {
        //Arrange
        $filterTransfer = $this->createSchedulerFilterTransfer();

        //Act
        $responseCollectionTransfer = $this->getSchedulerFacade()->setup($filterTransfer);

        //Assert
        $this->assertNotEmpty($responseCollectionTransfer->getResponses());

        foreach ($responseCollectionTransfer->getResponses() as $responseTransfer) {
            $this->assertSame(static::TEST_SCHEDULER, $responseTransfer->getSchedule()->getIdScheduler());
        }
    }

    /**
     * @return void
     */
    public function testSchedulerClean(): void
    {
        //Arrange
        $filterTransfer = $this->createSchedulerFilterTransfer();

        //Act
        $responseCollectionTransfer = $this->getSchedulerFacade()->clean($filterTransfer);

        //Assert
        $this->assertNotEmpty($responseCollectionTransfer->getResponses());

        foreach ($responseCollectionTransfer->getResponses() as $responseTransfer) {
            $this->assertSame(static::TEST_SCHEDULER, $responseTransfer->getSchedule()->getIdScheduler());
        }
    }

    /**
     * @return void
     */
    public function testSchedulerResume(): void
    {
        //Arrange
        $filterTransfer = $this->createSchedulerFilterTransfer();

        //Act
        $responseCollectionTransfer = $this->getSchedulerFacade()->resume($filterTransfer);

        //Assert
        $this->assertNotEmpty($responseCollectionTransfer->getResponses());

        foreach ($responseCollectionTransfer->getResponses() as $responseTransfer) {
            $this->assertSame(static::TEST_SCHEDULER, $responseTransfer->getSchedule()->getIdScheduler());
        }
    }

    /**
     * @return void
     */
    public function testSchedulerSuspend(): void
    {
        //Arrange
        $requestTransfer = $this->createSchedulerFilterTransfer();

        //Act
        $responseCollectionTransfer = $this->getSchedulerFacade()->suspend($requestTransfer);

        //Assert
        $this->assertNotEmpty($responseCollectionTransfer->getResponses());

        foreach ($responseCollectionTransfer->getResponses() as $responseTransfer) {
            $this->assertSame(static::TEST_SCHEDULER, $responseTransfer->getSchedule()->getIdScheduler());
        }
    }

    /**
     * @return void
     */
    public function testSchedulerSetupReturnsErrorIfSchedulerPluginThrowsException(): void
    {
        // Arrange
        $filterTransfer = $this->createFailingSchedulerFilterTransfer();

        // Act
        $schedulerResponseCollectionTransfer = $this->getSchedulerFacade()->setup($filterTransfer);

        // Assert
        $this->assertNotEmpty($schedulerResponseCollectionTransfer->getResponses());
        foreach ($schedulerResponseCollectionTransfer->getResponses() as $schedulerResponseTransfer) {
            $this->assertFalse($schedulerResponseTransfer->getStatus());
            $this->assertSame(static::EXCEPTION_MESSAGE, $schedulerResponseTransfer->getMessage());
        }
    }

    /**
     * @return void
     */
    public function testSchedulerCleanReturnsErrorIfSchedulerPluginThrowsException(): void
    {
        // Arrange
        $filterTransfer = $this->createFailingSchedulerFilterTransfer();

        // Act
        $schedulerResponseCollectionTransfer = $this->getSchedulerFacade()->clean($filterTransfer);

        // Assert
        $this->assertNotEmpty($schedulerResponseCollectionTransfer->getResponses());
        foreach ($schedulerResponseCollectionTransfer->getResponses() as $schedulerResponseTransfer) {
            $this->assertFalse($schedulerResponseTransfer->getStatus());
            $this->assertSame(static::EXCEPTION_MESSAGE, $schedulerResponseTransfer->getMessage());
        }
    }

    /**
     * @return void
     */
    public function testSchedulerSuspendReturnsErrorIfSchedulerPluginThrowsException(): void
    {
        // Arrange
        $filterTransfer = $this->createFailingSchedulerFilterTransfer();

        // Act
        $schedulerResponseCollectionTransfer = $this->getSchedulerFacade()->suspend($filterTransfer);

        // Assert
        $this->assertNotEmpty($schedulerResponseCollectionTransfer->getResponses());
        foreach ($schedulerResponseCollectionTransfer->getResponses() as $schedulerResponseTransfer) {
            $this->assertFalse($schedulerResponseTransfer->getStatus());
            $this->assertSame(static::EXCEPTION_MESSAGE, $schedulerResponseTransfer->getMessage());
        }
    }

    /**
     * @return void
     */
    public function testSchedulerResumeReturnsErrorIfSchedulerPluginThrowsException(): void
    {
        // Arrange
        $filterTransfer = $this->createFailingSchedulerFilterTransfer();

        // Act
        $schedulerResponseCollectionTransfer = $this->getSchedulerFacade()->resume($filterTransfer);

        // Assert
        $this->assertNotEmpty($schedulerResponseCollectionTransfer->getResponses());
        foreach ($schedulerResponseCollectionTransfer->getResponses() as $schedulerResponseTransfer) {
            $this->assertFalse($schedulerResponseTransfer->getStatus());
            $this->assertSame(static::EXCEPTION_MESSAGE, $schedulerResponseTransfer->getMessage());
        }
    }

    /**
     * @return \Spryker\Zed\Scheduler\Business\SchedulerFacadeInterface
     */
    protected function getSchedulerFacade(): SchedulerFacadeInterface
    {
        $this->tester->mockFactoryMethod('getSchedulerAdapterPlugins', $this->getSchedulerAdapterPlugins());
        $this->tester->mockFactoryMethod('getScheduleReaderPlugins', $this->getSchedulerReaderPlugins());
        $this->tester->mockFactoryMethod('getConfig', $this->getSchedulerConfigMock());
        $this->tester->mockFactoryMethod('createSchedulerFilter', $this->getSchedulerFilter());

        /** @var \Spryker\Zed\Scheduler\Business\SchedulerFacadeInterface $facade */
        $facade = $this->tester->getFacade();

        return $facade;
    }

    /**
     * @return \Generated\Shared\Transfer\SchedulerFilterTransfer
     */
    protected function createSchedulerFilterTransfer(): SchedulerFilterTransfer
    {
        return (new SchedulerFilterTransfer())
                ->setSchedulers([static::TEST_SCHEDULER])
                ->setStore(static::STORE_NAME)
                ->setRoles([static::ROLE_ADMIN]);
    }

    /**
     * @return \Generated\Shared\Transfer\SchedulerFilterTransfer
     */
    protected function createFailingSchedulerFilterTransfer(): SchedulerFilterTransfer
    {
        return (new SchedulerFilterTransfer())
            ->setSchedulers([static::TEST_FAILING_SCHEDULER])
            ->setStore(static::STORE_NAME)
            ->setRoles([static::ROLE_ADMIN]);
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Scheduler\SchedulerConfig
     */
    protected function getSchedulerConfigMock(): SchedulerConfig
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
     * @return array<\Spryker\Zed\SchedulerExtension\Dependency\Plugin\ScheduleReaderPluginInterface>
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
    protected function getPhpSchedulerReaderPluginMock(): ScheduleReaderPluginInterface
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
            ->addJob((new SchedulerJobTransfer())->setName('DE_test1')->setStore('DE'))
            ->addJob((new SchedulerJobTransfer())->setName('DE_test1')->setStore('AT'));
    }

    /**
     * @return \Spryker\Zed\Scheduler\Business\Command\Filter\SchedulerFilterInterface
     */
    protected function getSchedulerFilter(): SchedulerFilterInterface
    {
        return new SchedulerFilter(
            $this->getSchedulerConfigMock(),
            $this->getSchedulerAdapterPlugins(),
        );
    }

    /**
     * @return array
     */
    protected function getSchedulerAdapterPlugins(): array
    {
        return [
            static::TEST_SCHEDULER => $this->getSchedulerAdapterPluginMock(),
            static::TEST_FAILING_SCHEDULER => $this->getFailingSetupSchedulerAdapterPluginMock(),
            'test1' => 'test1',
        ];
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SchedulerExtension\Dependency\Plugin\SchedulerAdapterPluginInterface
     */
    protected function getSchedulerAdapterPluginMock(): SchedulerAdapterPluginInterface
    {
        $schedulerAdapterPluginMock = $this->getMockBuilder(SchedulerAdapterPluginInterface::class)
            ->onlyMethods([
                static::SCHEDULER_METHOD_SETUP,
                static::SCHEDULER_METHOD_CLEAN,
                static::SCHEDULER_METHOD_SUSPEND,
                static::SCHEDULER_METHOD_RESUME,
            ])
            ->getMock();

        $schedulerAdapterPluginMock
            ->method(static::SCHEDULER_METHOD_SETUP)
            ->willReturn($this->createSchedulerResponseTransfer());

        $schedulerAdapterPluginMock
            ->method(static::SCHEDULER_METHOD_CLEAN)
            ->willReturn($this->createSchedulerResponseTransfer());

        $schedulerAdapterPluginMock
            ->method(static::SCHEDULER_METHOD_SUSPEND)
            ->willReturn($this->createSchedulerResponseTransfer());

        $schedulerAdapterPluginMock
            ->method(static::SCHEDULER_METHOD_RESUME)
            ->willReturn($this->createSchedulerResponseTransfer());

        return $schedulerAdapterPluginMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SchedulerExtension\Dependency\Plugin\SchedulerAdapterPluginInterface
     */
    protected function getFailingSetupSchedulerAdapterPluginMock(): SchedulerAdapterPluginInterface
    {
        $schedulerAdapterPluginMock = $this->getMockBuilder(SchedulerAdapterPluginInterface::class)
            ->onlyMethods([
                static::SCHEDULER_METHOD_SETUP,
                static::SCHEDULER_METHOD_CLEAN,
                static::SCHEDULER_METHOD_SUSPEND,
                static::SCHEDULER_METHOD_RESUME,
            ])
            ->getMock();

        $schedulerAdapterPluginMock
            ->method(static::SCHEDULER_METHOD_SETUP)
            ->willThrowException(new Exception(static::EXCEPTION_MESSAGE));

        $schedulerAdapterPluginMock
            ->method(static::SCHEDULER_METHOD_CLEAN)
            ->willThrowException(new Exception(static::EXCEPTION_MESSAGE));

        $schedulerAdapterPluginMock
            ->method(static::SCHEDULER_METHOD_SUSPEND)
            ->willThrowException(new Exception(static::EXCEPTION_MESSAGE));

        $schedulerAdapterPluginMock
            ->method(static::SCHEDULER_METHOD_RESUME)
            ->willThrowException(new Exception(static::EXCEPTION_MESSAGE));

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
        return codecept_data_dir() . 'cronjobs' . DIRECTORY_SEPARATOR . static::TEST_SCHEDULER . '.php';
    }

    /**
     * @return array<string>
     */
    protected function getEnabledSchedulers(): array
    {
        return [
            static::TEST_SCHEDULER,
            static::TEST_FAILING_SCHEDULER,
        ];
    }
}
