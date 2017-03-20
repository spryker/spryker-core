<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Search\Communication\Console;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Search\Communication\Console\GenerateIndexMapConsole;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Search
 * @group Communication
 * @group Console
 * @group GenerateIndexMapConsoleTest
 */
class GenerateIndexMapConsoleTest extends PHPUnit_Framework_TestCase
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
