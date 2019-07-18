<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Installer\Communication\Console;

use Codeception\TestCase\Test;
use Spryker\Zed\Installer\Communication\Console\InitializeDatabaseConsole;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Installer
 * @group Communication
 * @group Console
 * @group InitializeDatabaseConsoleTest
 * Add your own group annotations below this line
 */
class InitializeDatabaseConsoleTest extends Test
{
    /**
     * @var \SprykerTest\Zed\Installer\InstallerCommunicationTester
     */
    protected $tester;

    /**
     * @dataProvider _classFullNameDataProvider
     *
     * @uses InitializeDatabaseConsole::getPluginNameFromClass()
     *
     * @param string $example
     *
     * @return void
     */
    public function testGetPluginNameFromClass(string $example): void
    {
        // Prepare
        $expectedModuleName = 'ModuleName';
        $initializeDatabaseConsoleCommand = new InitializeDatabaseConsole();

        // Action
        $moduleName = $this->tester->invokeMethod(
            $initializeDatabaseConsoleCommand,
            'getPluginNameFromClass',
            [$example]
        );

        // Assert
        $this->assertSame($moduleName, $expectedModuleName);
    }

    /**
     * @return array
     */
    public function _classFullNameDataProvider(): array
    {
        return [
            ['Spryker\Zed\ModuleName\Communication\Plugin\ModuleNameInstallerPlugin'],
            ['Spryker\Zed\ModuleName\Communication\Plugin\Installer\ModuleNameInstallerPlugin'],
            ['Spryker\Zed\ModuleName\Communication\Plugin\Installer\Sample\ModuleNameInstallerPlugin'],
            ['Spryker\Zed\ModuleName\Communication\Plugins\ModuleNameInstallerPlugin'],
        ];
    }
}
