<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Search\Communication\Console;

use Codeception\Test\Unit;
use Spryker\Zed\Search\Communication\Console\GenerateIndexMapConsole;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Search
 * @group Communication
 * @group Console
 * @group GenerateIndexMapConsoleTest
 * Add your own group annotations below this line
 */
class GenerateIndexMapConsoleTest extends Unit
{
    /**
     * @return void
     */
    public function testCommandIsExecutable()
    {
        $application = new Application();
        $application->add(new GenerateIndexMapConsole());

        $command = $application->find(GenerateIndexMapConsole::COMMAND_NAME);
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $this->assertSame(GenerateIndexMapConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }
}
