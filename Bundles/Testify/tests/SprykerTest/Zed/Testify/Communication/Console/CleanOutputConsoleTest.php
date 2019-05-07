<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Testify\Communication\Console;

use Codeception\Test\Unit;
use Spryker\Zed\Testify\Communication\Console\CleanOutputConsole;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Testify
 * @group Communication
 * @group Console
 * @group CleanOutputConsoleTest
 * Add your own group annotations below this line
 */
class CleanOutputConsoleTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Testify\TestifyCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExecuteWillRemoveGeneratedOutputFilesInSpecifiedDirectories(): void
    {
        file_put_contents(codecept_data_dir('/Fixtures/foo.file'), 'fileContent');

        $this->tester->mockConfigMethod('getOutputDirectoriesForCleanup', [codecept_data_dir('/Fixtures')]);
        $facade = $this->tester->getFacade();

        $command = new CleanOutputConsole();
        $command->setFacade($facade);

        $commandTester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
        ];

        $commandTester->execute($arguments);

        $this->assertSame(CleanOutputConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }
}
