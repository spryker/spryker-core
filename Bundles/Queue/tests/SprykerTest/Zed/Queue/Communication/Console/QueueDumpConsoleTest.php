<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Queue\Communication\Console;

use Codeception\Test\Unit;
use Spryker\Zed\Queue\Business\QueueFacadeInterface;
use Spryker\Zed\Queue\Communication\Console\QueueDumpConsole;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Queue
 * @group Communication
 * @group Console
 * @group QueueDumpConsoleTest
 * Add your own group annotations below this line
 */
class QueueDumpConsoleTest extends Unit
{
    /**
     * @return void
     */
    public function testCommandIsExecutable(): void
    {
        $this->markTestSkipped(
            'When running in context of whole suite this error comes up "posix_isatty(): could not use stream of type \'MEMORY\'"' . PHP_EOL
            . 'When it runs as standalone this error does not exists...'
        );

        $application = new Application();
        $application->add($this->getQueueDumpConsoleMock());

        $command = $application->find(QueueDumpConsole::COMMAND_NAME);
        $commandTester = new CommandTester($command);

        $commandProperties = [
            'queue' => 'event',
            '-l' => 1,
        ];

        $commandTester->execute($commandProperties);

        $this->assertSame(QueueDumpConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Queue\Communication\Console\QueueDumpConsole
     */
    protected function getQueueDumpConsoleMock()
    {
        $mockBuilder = $this->getMockBuilder(QueueDumpConsole::class)
            ->setMethods(['getFacade']);

        $mock = $mockBuilder->getMock();
        $mock->expects($this->once())->method('getFacade')->willReturn($this->getFacadeMock());

        return $mock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacadeMock()
    {
        $mockBuilder = $this->getMockBuilder(QueueFacadeInterface::class);

        return $mockBuilder->getMock();
    }
}
