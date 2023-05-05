<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Search\Communication\Console;

use Codeception\Stub;
use Codeception\Test\Unit;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapCleaner;
use Spryker\Zed\Search\Business\SearchBusinessFactory;
use Spryker\Zed\Search\Business\SearchFacade;
use Spryker\Zed\Search\Communication\Console\GenerateIndexMapConsole;
use Spryker\Zed\Search\SearchConfig;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Auto-generated group annotations
 *
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
     * @var \SprykerTest\Zed\Search\SearchCommunicationTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @return void
     */
    public function testCommandIsExecutable(): void
    {
        $this->markTestSkipped('This test is skipped until P&S is able to handle search tests.');

        //Arrange
        $generatedDirectory = __DIR__ . '/Generated/';
        $configMock = Stub::make(SearchConfig::class, [
            'getClassTargetDirectory' => function () use ($generatedDirectory) {
                return $generatedDirectory;
            },
        ]);

        $searchBusinessFactory = new SearchBusinessFactory();
        $searchBusinessFactory->setConfig($configMock);

        $searchFacade = new SearchFacade();
        $searchFacade->setFactory($searchBusinessFactory);

        $consoleCommand = new GenerateIndexMapConsole();
        $consoleCommand->setFacade($searchFacade);

        $application = new Application();
        $application->add($consoleCommand);

        //Act
        $command = $application->find(GenerateIndexMapConsole::COMMAND_NAME);
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        //Assert
        $this->assertSame(GenerateIndexMapConsole::CODE_SUCCESS, $commandTester->getStatusCode());

        (new IndexMapCleaner($generatedDirectory))->cleanDirectory();
    }
}
