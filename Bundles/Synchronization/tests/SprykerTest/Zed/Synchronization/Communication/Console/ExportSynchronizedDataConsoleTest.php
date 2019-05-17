<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Synchronization\Communication\Console;

use Codeception\Test\Unit;
use Spryker\Zed\Synchronization\Communication\Console\ExportSynchronizedDataConsole;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Synchronization
 * @group Communication
 * @group Console
 * @group ExportSynchronizedDataConsoleTest
 * Add your own group annotations below this line
 */
class ExportSynchronizedDataConsoleTest extends Unit
{
    /**
     * @return void
     */
    public function testCommandIsExecutable()
    {
        $application = new Application();
        $application->add(new ExportSynchronizedDataConsole());

        $command = $application->find(ExportSynchronizedDataConsole::COMMAND_NAME);
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $this->assertSame(ExportSynchronizedDataConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }
}
