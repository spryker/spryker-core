<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Plugin\Console;

use Codeception\Test\Unit;
use Spryker\Glue\GlueApplication\Plugin\Console\RouterDebugGlueApplicationConsole;
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
 * @group RouterDebugConsoleTest
 * Add your own group annotations below this line
 */
class RouterDebugConsoleTest extends Unit
{
    /**
     * @var string
     */
    protected const APPLICATION_NAME_BACKEND = 'backend';

    /**
     * @var string
     */
    protected const APPLICATION_NAME_STOREFRONT = 'storefront';

    /**
     * @return void
     */
    public function testCommandIsExecutableForBackend(): void
    {
        //Arrange
        $commandTester = $this->getExecutableCommandTester();

        //Act
        $commandTester->execute([
            'application_name' => static::APPLICATION_NAME_BACKEND,
        ]);

        //Assert
        $this->assertSame(RouterDebugGlueApplicationConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }

    /**
     * @return void
     */
    public function testCommandIsExecutableForStorefront(): void
    {
        //Arrange
        $commandTester = $this->getExecutableCommandTester();

        //Act
        $commandTester->execute([
            'application_name' => static::APPLICATION_NAME_STOREFRONT,
        ]);

        //Assert
        $this->assertSame(RouterDebugGlueApplicationConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }

    /**
     * @return \Symfony\Component\Console\Tester\CommandTester
     */
    protected function getExecutableCommandTester(): CommandTester
    {
        $routerDebugConsole = new RouterDebugGlueApplicationConsole();

        $application = new Application();
        $application->add($routerDebugConsole);

        $command = $application->find($routerDebugConsole->getName());

        return new CommandTester($command);
    }
}
