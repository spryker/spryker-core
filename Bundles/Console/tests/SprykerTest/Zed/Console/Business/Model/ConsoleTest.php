<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Console\Business\Model;

use Codeception\Test\Unit;
use Spryker\Zed\Console\Business\ConsoleBusinessFactory;
use Spryker\Zed\Console\Business\ConsoleFacade;
use Spryker\Zed\Console\ConsoleDependencyProvider;
use Spryker\Zed\Console\Dependency\Plugin\ConsolePostRunHookPluginInterface;
use Spryker\Zed\Console\Dependency\Plugin\ConsolePreRunHookPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerTest\Zed\Console\Business\Model\Fixtures\ConsoleMock;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Console
 * @group Business
 * @group Model
 * @group ConsoleTest
 * Add your own group annotations below this line
 */
class ConsoleTest extends Unit
{
    /**
     * @return void
     */
    public function testGetCommunicationFactoryShouldReturnInstanceIfSet()
    {
        $console = $this->getConsole();
        $console->setFactory($this->getCommunicationFactoryMock());

        $this->assertInstanceOf(
            'Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory',
            $console->getFactory()
        );
    }

    /**
     * @return void
     */
    public function testPreRunPluginsWillExecutesBeforeConsoleCommands()
    {
        $container = new Container();
        $container[ConsoleDependencyProvider::PLUGINS_CONSOLE_PRE_RUN_HOOK] = function (Container $container) {
            $preRunPluginMock = $this->getPreRunPluginMock();

            return [$preRunPluginMock];
        };

        $container[ConsoleDependencyProvider::PLUGINS_CONSOLE_POST_RUN_HOOK] = function (Container $container) {
            return [];
        };

        $consoleFacade = $this->prepareFacade($container);
        $inputMock = $this->getMockBuilder(InputInterface::class)->getMock();
        $outputMock = $this->getMockBuilder(OutputInterface::class)->getMock();
        $consoleFacade->preRun($inputMock, $outputMock);
    }

    /**
     * @return void
     */
    public function testPostRunPluginsWillExecutesBeforeConsoleCommands()
    {
        $container = new Container();
        $container[ConsoleDependencyProvider::PLUGINS_CONSOLE_PRE_RUN_HOOK] = function (Container $container) {
            return [];
        };

        $container[ConsoleDependencyProvider::PLUGINS_CONSOLE_POST_RUN_HOOK] = function (Container $container) {
            $postRunPluginMock = $this->getPostRunPluginMock();

            return [$postRunPluginMock];
        };

        $consoleFacade = $this->prepareFacade($container);
        $inputMock = $this->getMockBuilder(InputInterface::class)->getMock();
        $outputMock = $this->getMockBuilder(OutputInterface::class)->getMock();
        $consoleFacade->postRun($inputMock, $outputMock);
    }

    /**
     * @return void
     */
    public function testGetQueryContainerShouldReturnNullIfNotSet()
    {
        $console = $this->getConsole();

        $this->assertNull($console->getQueryContainer());
    }

    /**
     * @return void
     */
    public function testGetQueryContainerShouldReturnInstanceIfSet()
    {
        $console = $this->getConsole();
        $console->setQueryContainer($this->getQueryContainerMock());

        $this->assertInstanceOf(
            AbstractQueryContainer::class,
            $console->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory
     */
    private function getCommunicationFactoryMock()
    {
        return $this->getMockBuilder(AbstractCommunicationFactory::class)->disableOriginalConstructor()->getMock();
    }

    /**
     * @return \Spryker\Zed\Kernel\Persistence\AbstractQueryContainer
     */
    private function getQueryContainerMock()
    {
        return $this->getMockBuilder(AbstractQueryContainer::class)->disableOriginalConstructor()->getMock();
    }

    /**
     * @return \SprykerTest\Zed\Console\Business\Model\Fixtures\ConsoleMock
     */
    private function getConsole()
    {
        return new ConsoleMock('TestCommand');
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    private function getPreRunPluginMock()
    {
        $mock = $this->getMockBuilder(ConsolePreRunHookPluginInterface::class)->setMethods(['preRun'])->getMock();
        $mock->expects($this->once())->method('preRun');

        return $mock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    private function getPostRunPluginMock()
    {
        $mock = $this->getMockBuilder(ConsolePostRunHookPluginInterface::class)->setMethods(['postRun'])->getMock();
        $mock->expects($this->once())->method('postRun');

        return $mock;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Console\Business\ConsoleFacade
     */
    protected function prepareFacade(Container $container)
    {
        $consoleBusinessFactory = new ConsoleBusinessFactory();
        $consoleBusinessFactory->setContainer($container);

        $consoleFacade = new ConsoleFacade();
        $consoleFacade->setFactory($consoleBusinessFactory);

        return $consoleFacade;
    }
}
