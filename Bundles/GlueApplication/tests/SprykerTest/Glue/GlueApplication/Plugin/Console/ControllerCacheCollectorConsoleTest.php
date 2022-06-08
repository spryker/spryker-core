<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Plugin\Console;

use Codeception\Test\Unit;
use Spryker\Glue\GlueApplication\Plugin\Console\ControllerCacheCollectorConsole;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group Plugin
 * @group Console
 * @group ControllerCacheCollectorConsoleTest
 * Add your own group annotations below this line
 */
class ControllerCacheCollectorConsoleTest extends Unit
{
    /**
     * @return void
     */
    public function testCommandIsExecutable(): void
    {
        $controllerCacheCollectorConsole = new ControllerCacheCollectorConsole();

        $application = new Application();
        $application->add($controllerCacheCollectorConsole);

        $command = $application->find($controllerCacheCollectorConsole->getName());
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $this->assertSame(ControllerCacheCollectorConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }
}
