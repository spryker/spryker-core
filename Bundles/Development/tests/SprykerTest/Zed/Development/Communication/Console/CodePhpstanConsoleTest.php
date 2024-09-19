<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Development\Communication\Console;

use Codeception\Test\Unit;
use Spryker\Zed\Development\Communication\Console\CodePhpstanConsole;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Development
 * @group Communication
 * @group Console
 * @group CodePhpstanConsoleTest
 * Add your own group annotations below this line
 */
class CodePhpstanConsoleTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Development\DevelopmentCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function executesSuccessfullyWithoutModuleOption(): void
    {
        // Arrange
        $commandTester = $this->createCommandTester();

        // Act
        $exitCode = $commandTester->execute([]);

        // Assert
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Run PHPStan static analyzer for project or core', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function executesSuccessfullyWithModuleOption(): void
    {
        // Arrange
        $commandTester = $this->createCommandTester();

        // Act
        $exitCode = $commandTester->execute([
            '--module' => 'Spryker.Development',
        ]);

        // Assert
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Run PHPStan for Spryker.Development', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function executesSuccessfullyWithDryRunOption(): void
    {
        // Arrange
        $commandTester = $this->createCommandTester();

        // Act
        $exitCode = $commandTester->execute([
            '--dry-run' => true,
        ]);

        // Assert
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Dry-run the command, display it only', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function executesSuccessfullyWithLevelOption(): void
    {
        // Arrange
        $commandTester = $this->createCommandTester();

        // Act
        $exitCode = $commandTester->execute([
            '--level' => '7',
        ]);

        // Assert
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Level of rule options - the higher the stricter', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function failsWithInvalidLevelOption(): void
    {
        // Arrange
        $commandTester = $this->createCommandTester();

        // Act
        $exitCode = $commandTester->execute([
            '--level' => 'invalid',
        ]);

        // Assert
        $this->assertNotSame(0, $exitCode);
    }

    /**
     * @return \Symfony\Component\Console\Tester\CommandTester
     */
    protected function createCommandTester(): CommandTester
    {
        $application = new Application();
        $application->add(new CodePhpstanConsole());

        $command = $application->find('code:phpstan');

        return new CommandTester($command);
    }
}
