<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Queue\Business;

use Codeception\Test\Unit;
use ReflectionClass;
use Spryker\Zed\Queue\Business\Process\ProcessManager;
use Spryker\Zed\Queue\Business\Process\ProcessManagerInterface;
use Spryker\Zed\Queue\Business\QueueBusinessFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Queue
 * @group Business
 * @group QueueWorkerSignalDispatcherTest
 * Add your own group annotations below this line
 */
class QueueWorkerSignalDispatcherTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Queue\QueueBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        if (!function_exists('pcntl_signal') || !function_exists('posix_kill')) {
            $this->markTestSkipped('PCNTL extension is needed for the tests to run properly.');
        }
    }

    /**
     * @return void
     */
    public function testWaitForRunningProcessesIsExecuted(): void
    {
        $this->dispatchQueueWorkerHandling();
    }

    /**
     * @return void
     */
    public function testWaitForRunningProcessesIsAlreadyInProgress(): void
    {
        $isProcessAlreadyStarted = true;
        $this->dispatchQueueWorkerHandling($isProcessAlreadyStarted);
    }

    /**
     * @return void
     */
    public function testWaitForRunningProcessesChecksForEachSpecifiedQueue(): void
    {
        $this->tester->mockFactoryMethod('createProcessManager', $this->getProcessManagerMock());
        $this->tester->mockFactoryMethod('getQueueNames', $this->getQueueNames());

        $this->dispatchQueueWorkerHandling();
    }

    /**
     * @param bool $isProcessRunning
     *
     * @return void
     */
    protected function dispatchQueueWorkerHandling(bool $isProcessRunning = false): void
    {
        $queueWorkerSignalDispatcher = $this->getFactory()->createQueueWorkerSignalDispatcher();
        $reflectionClass = new ReflectionClass($queueWorkerSignalDispatcher);

        $reflectionMethod = $reflectionClass->getMethod('waitForRunningProcesses');
        $reflectionMethod->setAccessible(true);

        $reflectionProperty = $reflectionClass->getProperty('isProcessRunning');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($queueWorkerSignalDispatcher, $isProcessRunning);

        $reflectionMethod->invoke($queueWorkerSignalDispatcher);

        $this->assertSame($isProcessRunning, $reflectionProperty->getValue($queueWorkerSignalDispatcher));
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractBusinessFactory|\Spryker\Zed\Queue\Business\QueueBusinessFactory
     */
    protected function getFactory(): QueueBusinessFactory
    {
        return $this->tester->getFactory();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Queue\Business\Process\ProcessManagerInterface
     */
    protected function getProcessManagerMock(): ProcessManagerInterface
    {
        $processManagerMock = $this->getMockBuilder(ProcessManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $processManagerMock
            ->expects($this->exactly(count($this->getQueueNames())))
            ->method('getBusyProcessNumber');

        return $processManagerMock;
    }

    /**
     * @return string[]
     */
    protected function getQueueNames(): array
    {
        return [
            'queueTest',
            'queueTest1',
            'queueTest2',
        ];
    }
}
