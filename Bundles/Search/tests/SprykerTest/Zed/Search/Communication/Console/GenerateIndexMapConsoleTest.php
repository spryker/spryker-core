<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Search\Communication\Console;

use Codeception\Test\Unit;
use Spryker\Zed\Search\Communication\Console\GenerateIndexMapConsole;
use Spryker\Zed\Store\StoreDependencyProvider;
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
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var \SprykerTest\Zed\Search\SearchBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCommandIsExecutable(): void
    {
        //Arrange
        $application = new Application();
        $application->add(new GenerateIndexMapConsole());
        $this->tester->setDependency(StoreDependencyProvider::STORE_CURRENT, static::STORE_NAME_DE);

        //Act
        $command = $application->find(GenerateIndexMapConsole::COMMAND_NAME);
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        //Assert
        $this->assertSame(GenerateIndexMapConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }
}
