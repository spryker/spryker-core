<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Communication\Console;

use Codeception\Test\Unit;
use Spryker\Zed\Propel\Communication\Console\MigrationCheckConsole;
use SprykerTest\Zed\Propel\PropelCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Propel
 * @group Communication
 * @group Console
 * @group MigrationCheckConsoleTest
 * Add your own group annotations below this line
 */
class MigrationCheckConsoleTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Propel\Communication\Console\MigrationCheckConsole::COMMAND_OPTION_LAST_VERSION_FULL
     *
     * @var string
     */
    protected const COMMAND_OPTION_LAST_VERSION_FULL = '--last-version';

    /**
     * @var \SprykerTest\Zed\Propel\PropelCommunicationTester
     */
    protected PropelCommunicationTester $tester;

    /**
     * @return void
     */
    public function testExecuteShouldReturnStatusCodeErrorWhileNoMigrationFileGotFound(): void
    {
        // Arrange
        $this->tester->mockConfigMethod(
            'getPropelConfig',
            $this->getPropelConfig(codecept_data_dir()),
        );

        $commandTester = $this->tester->getMigrationCheckConsoleCommandTester();

        // Act
        $commandTester->execute([]);

        // Assert
        $display = $commandTester->getDisplay();

        $this->assertSame(MigrationCheckConsole::CODE_ERROR, $commandTester->getStatusCode());
        $this->assertStringContainsString('propel:migration:check', $display);
        $this->assertStringContainsString('Checking Database Versions...', $display);
        $this->assertStringContainsString('Listing Migration files...', $display);
        $this->assertStringContainsString('No migration file found in "' . codecept_data_dir() . '".', $display);
    }

    /**
     * @return void
     */
    public function testExecuteShouldReturnStatusCodeSuccessWhileMigrationFileGotFound(): void
    {
        // Arrange
        $this->tester->ensurePropelMigrationTableIsEmpty();

        $this->tester->mockConfigMethod(
            'getPropelConfig',
            $this->getPropelConfig(codecept_data_dir('migration')),
        );

        $commandTester = $this->tester->getMigrationCheckConsoleCommandTester();

        // Act
        $commandTester->execute([]);

        // Assert
        $display = $commandTester->getDisplay();

        $this->assertSame(MigrationCheckConsole::CODE_SUCCESS, $commandTester->getStatusCode());
        $this->assertStringContainsString('propel:migration:check', $display);
        $this->assertStringContainsString('Checking Database Versions...', $display);
        $this->assertStringContainsString('Listing Migration files...', $display);
        $this->assertStringContainsString('1 valid migration classes found in "' . codecept_data_dir('migration') . '"', $display);
        $this->assertStringContainsString('1 migration needs to be executed:', $display);
        $this->assertStringContainsString('PropelMigration_1673610629', $display);
        $this->assertStringContainsString('Call the "migrate" task to execute it', $display);
    }

    /**
     * @return void
     */
    public function testExecuteShouldReturnTheLastMigrationVersionWhenLastVersionOptionIsProvided(): void
    {
        // Arrange
        $this->tester->ensurePropelMigrationTableIsEmpty();

        $version = $this->tester->havePropelMigrationPersisted(1673610629);
        $commandTester = $this->tester->getMigrationCheckConsoleCommandTester();

        // Act
        $commandTester->execute([static::COMMAND_OPTION_LAST_VERSION_FULL => true]);

        // Assert
        $display = $commandTester->getDisplay();

        $this->assertSame(MigrationCheckConsole::CODE_SUCCESS, $commandTester->getStatusCode());
        $this->assertStringContainsString('propel:migration:check', $display);
        $this->assertStringContainsString($version, $display);
    }

    /**
     * @return void
     */
    public function testExecuteShouldReturnEmptyWhenLastVersionOptionIsProvidedAndMigrationsWereNotExecuted(): void
    {
        // Arrange
        $this->tester->ensurePropelMigrationTableIsEmpty();

        $commandTester = $this->tester->getMigrationCheckConsoleCommandTester();

        // Act
        $commandTester->execute([static::COMMAND_OPTION_LAST_VERSION_FULL => true]);

        // Assert
        $display = $commandTester->getDisplay();

        $this->assertSame(MigrationCheckConsole::CODE_SUCCESS, $commandTester->getStatusCode());
        $this->assertStringContainsString('propel:migration:check', $display);
    }

    /**
     * @param string $migrationDir
     *
     * @return array<string, array<string, mixed>>
     */
    protected function getPropelConfig(string $migrationDir): array
    {
        $propelConfig = $this->tester->getModuleConfig()->getPropelConfig();
        $propelConfig['paths']['migrationDir'] = $migrationDir;

        return $propelConfig;
    }
}
