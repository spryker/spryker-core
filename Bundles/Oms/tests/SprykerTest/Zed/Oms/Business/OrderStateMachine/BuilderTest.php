<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business\OrderStateMachine;

use Codeception\Test\Unit;
use Spryker\Zed\Oms\Business\Exception\StatemachineException;
use Spryker\Zed\Oms\Business\OrderStateMachine\Builder;
use Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface;
use Spryker\Zed\Oms\Business\Process\EventInterface;
use Spryker\Zed\Oms\Business\Process\Process;
use Spryker\Zed\Oms\Business\Process\ProcessInterface;
use Spryker\Zed\Oms\Business\Process\StateInterface;
use Spryker\Zed\Oms\Business\Process\TransitionInterface;
use Spryker\Zed\Oms\Business\Reader\ProcessCacheReader;
use Spryker\Zed\Oms\Business\Reader\ProcessCacheReaderInterface;
use Spryker\Zed\Oms\Business\Util\DrawerInterface;
use Spryker\Zed\Oms\Business\Writer\ProcessCacheWriter;
use Spryker\Zed\Oms\OmsConfig;
use SprykerTest\Zed\Oms\OmsBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Business
 * @group OrderStateMachine
 * @group BuilderTest
 * Add your own group annotations below this line
 */
class BuilderTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Oms\OmsBusinessTester
     */
    protected OmsBusinessTester $tester;

    /**
     * @return void
     */
    public function tearDown(): void
    {
        $processACopyTarget = $this->getProcessLocationB() . DIRECTORY_SEPARATOR . 'process-a.xml';
        if (file_exists($processACopyTarget)) {
            unlink($processACopyTarget);
        }
    }

    /**
     * This test can be removed when optional argument `$processDefinitionLocation` is mandatory
     *
     * @return void
     */
    public function testInstantiationWithoutXmlFolder(): void
    {
        $builder = $this->createBuilder();

        $this->assertInstanceOf(Builder::class, $builder);
    }

    /**
     * @return void
     */
    public function testInstantiationWithXmlFolderAsString(): void
    {
        $builder = $this->createBuilder('');

        $this->assertInstanceOf(Builder::class, $builder);
    }

    /**
     * @return void
     */
    public function testInstantiationWithXmlFolderAsArray(): void
    {
        $builder = $this->createBuilder([]);

        $this->assertInstanceOf(Builder::class, $builder);
    }

    /**
     * @return void
     */
    public function testGetProcessShouldThrowExceptionWhenProcessFoundInMoreThenOneLocation(): void
    {
        $builder = $this->createBuilder([$this->getProcessLocationA(), $this->getProcessLocationB()]);

        $processACopyTarget = $this->getProcessLocationB() . DIRECTORY_SEPARATOR . 'process-a.xml';
        copy($this->getProcessLocationA() . DIRECTORY_SEPARATOR . 'process-a.xml', $processACopyTarget);
        $this->expectException(StatemachineException::class);
        $this->expectExceptionMessage('"process-a.xml" found in more then one location. Could not determine which one to choose. Please check your process definition location');
        $builder->createProcess('process-a');
    }

    /**
     * @return void
     */
    public function testGetProcessShouldThrowExceptionWhenNoProcessFound(): void
    {
        $builder = $this->createBuilder([$this->getProcessLocationB()]);

        $this->expectException(StatemachineException::class);
        $this->expectExceptionMessage('Could not find "process-a.xml". Please check your process definition location');
        $builder->createProcess('process-a');
    }

    /**
     * @return void
     */
    public function testGetProcess(): void
    {
        $builder = $this->createBuilder([$this->getProcessLocationA(), $this->getProcessLocationB()]);

        $result = $builder->createProcess('process-a');
        $this->assertInstanceOf(ProcessInterface::class, $result);
    }

    /**
     * @dataProvider omsProcessCachingReaderDataProvider
     *
     * @param bool $cacheIsEnabled
     * @param int $expectedReaderCalls
     *
     * @return void
     */
    public function testCreateProcessShouldReadFromCacheIfCacheIsEnabled(
        bool $cacheIsEnabled,
        int $expectedReaderCalls
    ): void {
        // Arrange
        $process = $this->getProcess();
        $omsConfigMock = $this->createMock(OmsConfig::class);
        $omsConfigMock
            ->method('isProcessCacheEnabled')
            ->willReturn($cacheIsEnabled);

        $processCacheReaderMock = $this->getMockBuilder(ProcessCacheReader::class)
            ->setConstructorArgs([new OmsConfig()])
            ->onlyMethods(['getProcess'])
            ->getMock();

        if (file_exists($processCacheReaderMock->getFullFilename('process-a'))) {
            unlink($processCacheReaderMock->getFullFilename('process-a'));
        }

        $builder = new Builder(
            $this->getEventMock(),
            $this->getStateMock(),
            $this->getTransitionMock(),
            $process,
            [$this->getProcessLocationA()],
            $processCacheReaderMock,
            $this->tester->createProcessCacheWriter(),
            $omsConfigMock,
        );

        // Assert
        $processCacheReaderMock
            ->expects($this->exactly($expectedReaderCalls))
            ->method('getProcess')
            ->with('process-a')
            ->willReturn($process);

        // Act
        $this->tester->resetProcessBuffer();
        $builder->createProcess('process-a');

        $this->tester->resetProcessBuffer();
        $builder->createProcess('process-a');

        $this->tester->resetProcessBuffer();
        $builder->createProcess('process-a');
    }

    /**
     * @dataProvider omsProcessCachingWriterDataProvider
     *
     * @param bool $cacheIsEnabled
     * @param bool $regenerateCache
     * @param int|bool $expectedReaderCalls
     * @param int $expectedWriterCalls
     *
     * @return void
     */
    public function testCreateProcessShouldWriteToCacheIfCacheIsEnabled(
        bool $cacheIsEnabled,
        bool $regenerateCache,
        int $expectedReaderCalls,
        int $expectedWriterCalls
    ): void {
        // Arrange
        $omsConfigMock = $this->createMock(OmsConfig::class);
        $omsConfigMock
            ->method('isProcessCacheEnabled')
            ->willReturn($cacheIsEnabled);

        $processCacheReaderMock = $this->createMock(ProcessCacheReaderInterface::class);

        $processCacheWriterMock = $this->getMockBuilder(ProcessCacheWriter::class)
            ->setConstructorArgs([$omsConfigMock, $this->tester->createProcessCacheReader()])
            ->onlyMethods(['cacheProcess'])
            ->getMock();

        $invocationCount = 0;

        // Assert
        $processCacheReaderMock
            ->expects($this->exactly($expectedReaderCalls))
            ->method('hasProcess')
            ->willReturnCallback(function () use (&$invocationCount) {
                $invocationCount++;

                return $invocationCount === 2;
            });

        $processCacheWriterMock
            ->expects($this->exactly($expectedWriterCalls))
            ->method('cacheProcess')
            ->willReturn($this->tester->createProcessCacheReader()->getFullFilename('process-a'));

        $builder = new Builder(
            $this->getEventMock(),
            $this->getStateMock(),
            $this->getTransitionMock(),
            $this->getProcess(),
            [$this->getProcessLocationA()],
            $processCacheReaderMock,
            $processCacheWriterMock,
            $omsConfigMock,
        );

        // Act
        $this->tester->resetProcessBuffer();
        $builder->createProcess('process-a', $regenerateCache);

        $this->tester->resetProcessBuffer();
        $builder->createProcess('process-a', $regenerateCache);
    }

    /**
     * @return array<string, array<string|mixed>>
     */
    protected function omsProcessCachingWriterDataProvider(): array
    {
        return [
            'test when cache is enabled and should be regenerated' => [
                'cacheIsEnabled' => true,
                'regenerateCache' => true,
                'expectedReaderCalls' => 2,
                'expectedWriterCalls' => 2,
            ],
            'test when cache is enabled and should not be regenerated' => [
                'cacheIsEnabled' => true,
                'regenerateCache' => false,
                'expectedReaderCalls' => 2,
                'expectedWriterCalls' => 1,
            ],
            'test when cache is disabled' => [
                'cacheIsEnabled' => false,
                'regenerateCache' => false,
                'expectedReaderCalls' => 0,
                'expectedWriterCalls' => 0,
            ],
        ];
    }

    /**
     * @return array<string, array<string|mixed>>
     */
    protected function omsProcessCachingReaderDataProvider(): array
    {
        return [
            'test when cache is enabled' => [
                'cacheIsEnabled' => true,
                'expectedReaderCalls' => 2,
            ],
            'test when cache is disabled' => [
                'cacheIsEnabled' => false,
                'expectedReaderCalls' => 0,
            ],
        ];
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Oms\Business\Process\EventInterface
     */
    private function getEventMock(): EventInterface
    {
        return $this->getMockBuilder(EventInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Oms\Business\Process\StateInterface
     */
    private function getStateMock(): StateInterface
    {
        return $this->getMockBuilder(StateInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Oms\Business\Process\TransitionInterface
     */
    private function getTransitionMock(): TransitionInterface
    {
        return $this->getMockBuilder(TransitionInterface::class)->getMock();
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Process\ProcessInterface
     */
    private function getProcess(): ProcessInterface
    {
        $drawerMock = $this->getDrawerMock();

        return new Process($drawerMock);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Oms\Business\Util\DrawerInterface
     */
    private function getDrawerMock(): DrawerInterface
    {
        return $this->getMockBuilder(DrawerInterface::class)->getMock();
    }

    /**
     * @return string
     */
    private function getProcessLocationA(): string
    {
        return __DIR__ . '/Builder/Fixtures/DefinitionLocationA';
    }

    /**
     * @return string
     */
    private function getProcessLocationB(): string
    {
        return __DIR__ . '/Builder/Fixtures/DefinitionLocationB';
    }

    /**
     * @param array|string|null $processDefinitionLocation
     *
     * @return \Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface
     */
    private function createBuilder(array|string|null $processDefinitionLocation = null): BuilderInterface
    {
        $eventMock = $this->getEventMock();
        $stateMock = $this->getStateMock();
        $transitionMock = $this->getTransitionMock();
        $process = $this->getProcess();

        return new Builder(
            $eventMock,
            $stateMock,
            $transitionMock,
            $process,
            $processDefinitionLocation,
            $this->tester->createProcessCacheReader(),
            $this->tester->createProcessCacheWriter(),
            new OmsConfig(),
        );
    }
}
