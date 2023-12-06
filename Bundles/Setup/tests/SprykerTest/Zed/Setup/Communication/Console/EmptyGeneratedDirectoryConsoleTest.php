<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Setup\Communication\Console;

use Codeception\Test\Unit;
use Spryker\Zed\Setup\Communication\Console\EmptyGeneratedDirectoryConsole;
use SprykerTest\Zed\Setup\SetupCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Setup
 * @group Communication
 * @group Console
 * @group EmptyGeneratedDirectoryConsoleTest
 * Add your own group annotations below this line
 */
class EmptyGeneratedDirectoryConsoleTest extends Unit
{
    /**
     * @var string
     */
    protected const DIRECTORY_FIXTURES = 'fixtures';

    /**
     * @var \SprykerTest\Zed\Setup\SetupCommunicationTester
     */
    protected SetupCommunicationTester $tester;

    /**
     * @return void
     */
    public function testExecuteShouldEmptyGeneratedDirectory(): void
    {
        // Arrange
        $generatedDirectory = codecept_data_dir(static::DIRECTORY_FIXTURES);
        $this->tester->mockConfigMethod('getGeneratedDirectory', $generatedDirectory);

        $testFile = $this->tester->createTestFile($generatedDirectory);

        $emptyGeneratedDirectoryConsole = new EmptyGeneratedDirectoryConsole();
        $emptyGeneratedDirectoryConsole->setFacade($this->tester->getFacade());

        $commandTester = $this->tester->getConsoleTester($emptyGeneratedDirectoryConsole);

        // Act
        $commandTester->execute(['command' => $emptyGeneratedDirectoryConsole->getName()]);

        // Assert
        $this->assertSame(EmptyGeneratedDirectoryConsole::CODE_SUCCESS, $commandTester->getStatusCode());
        $this->assertFalse(file_exists($testFile));
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        $this->tester->clearDirectory(codecept_data_dir(static::DIRECTORY_FIXTURES));
    }
}
