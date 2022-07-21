<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oauth\Communication\Console;

use Codeception\Test\Unit;
use Spryker\Zed\Oauth\Communication\Console\ScopeCacheCollectorConsole;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Oauth
 * @group Communication
 * @group Console
 * @group ScopeCacheCollectorConsoleTest
 * Add your own group annotations below this line
 */
class ScopeCacheCollectorConsoleTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Oauth\OauthBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExecuteSuccessful(): void
    {
        //Arrange
        $command = new ScopeCacheCollectorConsole();
        $commandTester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
        ];

        //Act
        $commandTester->execute($arguments);

        //Assert
        $this->assertSame(ScopeCacheCollectorConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }
}
