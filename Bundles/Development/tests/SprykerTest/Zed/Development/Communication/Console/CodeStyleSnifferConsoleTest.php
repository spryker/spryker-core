<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Development\Communication\Console;

use Codeception\Test\Unit;
use Spryker\Zed\Development\Communication\Console\CodeStyleSnifferConsole;
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
 * @group CodeStyleSnifferConsoleTest
 * Add your own group annotations below this line
 */
class CodeStyleSnifferConsoleTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Development\DevelopmentCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function executesSuccessfullyWithModuleName(): void
    {
        // Arrange
        $commandTester = $this->createCommandTester();

        // Act
        $exitCode = $commandTester->execute([
            '--module' => 'Development',
        ]);

        // Assert
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Run Code Style Sniffer for PROJECT in Development', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function executesSuccessfullyWithCoreModule(): void
    {
        // Arrange
        $commandTester = $this->createCommandTester();

        // Act
        $exitCode = $commandTester->execute([
            '--module' => 'Spryker.Development',
        ]);

        // Assert
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Run Code Style Sniffer for CORE in Spryker.Development', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function executesWithDryRunOption(): void
    {
        // Arrange
        $commandTester = $this->createCommandTester();

        // Act
        $exitCode = $commandTester->execute([
            '--dry-run' => true,
        ]);

        // Assert
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Dry-Run the command, display it only', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function failsWithInvalidModule(): void
    {
        // Arrange
        $commandTester = $this->createCommandTester();

        // Act
        $exitCode = $commandTester->execute([
            '--module' => 'InvalidModule',
        ]);

        // Assert
        $this->assertNotSame(0, $exitCode);
    }

    /**
     * @return void
     */
    public function executesWithFixOption(): void
    {
        // Arrange
        $commandTester = $this->createCommandTester();

        // Act
        $exitCode = $commandTester->execute([
            '--fix' => true,
        ]);

        // Assert
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Automatically fix errors that can be fixed', $commandTester->getDisplay());
    }

    /**
     * @return \Symfony\Component\Console\Tester\CommandTester
     */
    protected function createCommandTester(): CommandTester
    {
        $application = new Application();
        $application->add(new CodeStyleSnifferConsole());

        $command = $application->find('code:sniff:style');

        return new CommandTester($command);
    }
}
