<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Console\Communication\Plugin;

use Codeception\Test\Unit;
use Exception;
use Psr\Log\LoggerInterface;
use Spryker\Zed\Console\Communication\Plugin\ConsoleLogPlugin;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleErrorEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Console
 * @group Communication
 * @group Plugin
 * @group ConsoleLogPluginTest
 * Add your own group annotations below this line
 */
class ConsoleLogPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testGetSubscribedEventsShouldReturnArray(): void
    {
        $consoleLogPlugin = new ConsoleLogPlugin();

        $this->assertIsArray($consoleLogPlugin->getSubscribedEvents());
    }

    /**
     * @return void
     */
    public function testOnConsoleCommandShouldCallLogger(): void
    {
        $loggerMock = $this->getLoggerMock();
        $loggerMock->expects($this->once())->method('info');

        $consoleLogPlugin = $this->getConsoleLogPluginMock($loggerMock);
        $event = new ConsoleCommandEvent(new Command('test-command'), new ArrayInput([]), new ConsoleOutput());

        $consoleLogPlugin->onConsoleCommand($event);
    }

    /**
     * @return void
     */
    public function testOnConsoleTerminatedShouldCallLogger(): void
    {
        $loggerMock = $this->getLoggerMock();
        $loggerMock->expects($this->once())->method('info');

        $consoleLogPlugin = $this->getConsoleLogPluginMock($loggerMock);
        $event = new ConsoleTerminateEvent(new Command('test-command'), new ArrayInput([]), new ConsoleOutput(), 0);

        $consoleLogPlugin->onConsoleTerminate($event);
    }

    /**
     * @return void
     */
    public function testOnConsoleExceptionShouldCallLogger(): void
    {
        $loggerMock = $this->getLoggerMock();
        $loggerMock->expects($this->once())->method('error');

        $consoleLogPlugin = $this->getConsoleLogPluginMock($loggerMock);
        $event = new ConsoleErrorEvent(new ArrayInput([]), new ConsoleOutput(), new Exception(), new Command('test-command'));

        $consoleLogPlugin->onConsoleError($event);
    }

    /**
     * @return void
     */
    public function testOnConsoleExceptionWithoutCommandShouldCallLogger(): void
    {
        $loggerMock = $this->getLoggerMock();
        $loggerMock->expects($this->once())->method('error');

        $consoleLogPlugin = $this->getConsoleLogPluginMock($loggerMock);
        $event = new ConsoleErrorEvent(new ArrayInput([]), new ConsoleOutput(), new Exception());

        $consoleLogPlugin->onConsoleError($event);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Psr\Log\LoggerInterface
     */
    protected function getLoggerMock(): LoggerInterface
    {
        $lockerMock = $this->getMockBuilder(LoggerInterface::class)->getMock();

        return $lockerMock;
    }

    /**
     * @param \Psr\Log\LoggerInterface $loggerMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Console\Communication\Plugin\ConsoleLogPlugin
     */
    protected function getConsoleLogPluginMock(LoggerInterface $loggerMock): ConsoleLogPlugin
    {
        $consoleLogPluginMock = $this->getMockBuilder(ConsoleLogPlugin::class)
            ->setMethods(['getLogger'])
            ->getMock();

        $consoleLogPluginMock->expects($this->once())->method('getLogger')->willReturn($loggerMock);

        return $consoleLogPluginMock;
    }
}
