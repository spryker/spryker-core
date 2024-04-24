<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Plugin\Console;

use Codeception\Configuration;
use Codeception\Test\Unit;
use Spryker\Glue\GlueApplication\Cache\Writer\ControllerCacheWriterInterface;
use Spryker\Glue\GlueApplication\Plugin\Console\ControllerCacheCollectorConsole;
use SprykerTest\Glue\GlueApplication\GlueApplicationTester;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group Plugin
 * @group Console
 * @group ControllerCacheCollectorConsoleTest
 * Add your own group annotations below this line
 */
class ControllerCacheCollectorConsoleTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueApplication\GlueApplicationTester
     */
    protected GlueApplicationTester $tester;

    /**
     * @return void
     */
    public function testCommandIsExecutable(): void
    {
        // Arrange
        $this->tester->mockConfigMethod('getControllerCachePath', Configuration::dataDir());

        $controllerCacheCollectorConsole = new ControllerCacheCollectorConsole();
        $controllerCacheCollectorConsole->setFactory($this->tester->getFactory());

        $application = new Application();
        $application->add($controllerCacheCollectorConsole);

        $command = $application->find($controllerCacheCollectorConsole->getName());
        $commandTester = new CommandTester($command);

        // Act
        $commandTester->execute([]);

        // Assert
        $this->assertSame(ControllerCacheCollectorConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }

    /**
     * @return void
     */
    public function testCommandIsExecutableWithArgumentApplication(): void
    {
        // Arrange & Assert
        $controllerCacheWriterMock = $this->getMockBuilder(ControllerCacheWriterInterface::class)->getMock();
        $controllerCacheWriterMock
            ->expects($this->once())
            ->method('cache')
            ->will(
                $this->returnCallback(function ($apiApplication) {
                        $this->assertSame($this->tester::FAKE_APPLICATION, $apiApplication);
                }),
            );
        $this->tester->mockConfigMethod('getControllerCachePath', Configuration::dataDir());
        $this->tester->mockFactoryMethod('createControllerCacheWriter', $controllerCacheWriterMock);

        $controllerCacheCollectorConsole = new ControllerCacheCollectorConsole();
        $controllerCacheCollectorConsole->setFactory($this->tester->getFactory());

        $application = new Application();
        $application->add($controllerCacheCollectorConsole);

        $command = $application->find($controllerCacheCollectorConsole->getName());
        $commandTester = new CommandTester($command);

        // Act
        $commandTester->execute([
            'application' => $this->tester::FAKE_APPLICATION,
        ]);

        // Assert
        $this->assertSame(ControllerCacheCollectorConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->tester->removeCacheFile();
    }
}
