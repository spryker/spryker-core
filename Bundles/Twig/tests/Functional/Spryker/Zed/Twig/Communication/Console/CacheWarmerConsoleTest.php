<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Twig\Communication\Console;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Twig\Business\TwigFacadeInterface;
use Spryker\Zed\Twig\Communication\Console\CacheWarmerConsole;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Twig
 * @group Communication
 * @group Console
 * @group CacheWarmerConsoleTest
 */
class CacheWarmerConsoleTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testCommandIsExecutable()
    {
        $application = new Application();
        $application->add($this->getCacheWarmerConsoleMock());

        $command = $application->find(CacheWarmerConsole::COMMAND_NAME);
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $this->assertSame(CacheWarmerConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Twig\Communication\Console\CacheWarmerConsole
     */
    protected function getCacheWarmerConsoleMock()
    {
        $mockBuilder = $this->getMockBuilder(CacheWarmerConsole::class)
            ->setMethods(['getFacade']);

        $mock = $mockBuilder->getMock();
        $mock->expects($this->once())->method('getFacade')->willReturn($this->getFacadeMock());

        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacadeMock()
    {
        $mockBuilder = $this->getMockBuilder(TwigFacadeInterface::class);

        return $mockBuilder->getMock();
    }

}
