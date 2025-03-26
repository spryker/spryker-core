<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Console\Business\Model;

use Codeception\Test\Unit;
use ReflectionClass as ReflectionClassReflectionClass;
use Spryker\Zed\Console\Business\ConsoleBusinessFactory;
use Spryker\Zed\Console\Business\ConsoleFacade;
use Spryker\Zed\Console\Communication\Bootstrap\ConsoleBootstrap;
use Spryker\Zed\Console\ConsoleDependencyProvider;
use Spryker\Zed\Console\Dependency\Plugin\ConsolePostRunHookPluginInterface;
use Spryker\Zed\Console\Dependency\Plugin\ConsolePreRunHookPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerTest\Zed\Console\Business\Model\Fixtures\ConsoleMock;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
    public function testGetCommunicationFactoryShouldReturnInstanceIfSet(): void
    {
        $console = $this->getConsole();
        $console->setFactory($this->getCommunicationFactoryMock());

        $this->assertInstanceOf(
            AbstractCommunicationFactory::class,
            $console->getFactory(),
        );
    }

    /**
     * @return void
     */
    public function testPreRunPluginsWillExecutesBeforeConsoleCommands(): void
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
    public function testPostRunPluginsWillExecutesBeforeConsoleCommands(): void
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
    public function testGetQueryContainerShouldReturnNullIfNotSet(): void
    {
        $console = $this->getConsole();

        $this->assertNull($console->getQueryContainer());
    }

    /**
     * @return void
     */
    public function testGetQueryContainerShouldReturnInstanceIfSet(): void
    {
        $console = $this->getConsole();
        $console->setQueryContainer($this->getQueryContainerMock());

        $this->assertInstanceOf(
            AbstractQueryContainer::class,
            $console->getQueryContainer(),
        );
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    private function getCommunicationFactoryMock(): AbstractCommunicationFactory
    {
        return $this->getMockBuilder(AbstractCommunicationFactory::class)->disableOriginalConstructor()->getMock();
    }

    /**
     * @return \Spryker\Zed\Kernel\Persistence\AbstractQueryContainer|\PHPUnit\Framework\MockObject\MockObject
     */
    private function getQueryContainerMock(): AbstractQueryContainer
    {
        return $this->getMockBuilder(AbstractQueryContainer::class)->disableOriginalConstructor()->getMock();
    }

    /**
     * @return \SprykerTest\Zed\Console\Business\Model\Fixtures\ConsoleMock
     */
    private function getConsole(): ConsoleMock
    {
        return new ConsoleMock('TestCommand');
    }

    /**
     * @return \Spryker\Zed\Console\Dependency\Plugin\ConsolePreRunHookPluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function getPreRunPluginMock(): ConsolePreRunHookPluginInterface
    {
        $mock = $this->getMockBuilder(ConsolePreRunHookPluginInterface::class)->onlyMethods(['preRun'])->getMock();
        $mock->expects($this->once())->method('preRun');

        return $mock;
    }

    /**
     * @return \Spryker\Zed\Console\Dependency\Plugin\ConsolePostRunHookPluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function getPostRunPluginMock(): ConsolePostRunHookPluginInterface
    {
        $mock = $this->getMockBuilder(ConsolePostRunHookPluginInterface::class)->onlyMethods(['postRun'])->getMock();
        $mock->expects($this->once())->method('postRun');

        return $mock;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Console\Business\ConsoleFacade
     */
    protected function prepareFacade(Container $container): ConsoleFacade
    {
        $consoleBusinessFactory = new ConsoleBusinessFactory();
        $consoleBusinessFactory->setContainer($container);

        $consoleFacade = new ConsoleFacade();
        $consoleFacade->setFactory($consoleBusinessFactory);

        return $consoleFacade;
    }

    /**
     * @return void
     */
    public function testDoRunProcessWillExecuteSeveralTimesWhenRepeatableOptionIsTrue(): void
    {
        // Arrange
        $inputMock = $this->getMockBuilder(InputInterface::class)->getMock();
        $inputMock->method('hasParameterOption')->with()->willReturn(true);
        $inputMock->method('getParameterOption')->willReturn(1);

        $outputMock = $this->getMockBuilder(OutputInterface::class)->getMock();

        $commandMock = $this->createPartialMock(
            Console::class,
            [
                'execute',
                'getHelperSet',
            ],
        );

        $definition = new InputDefinition();
        $definition->addOption(new InputOption('repeatable', null, InputOption::VALUE_NONE));
        $commandMock->setDefinition($definition);
        $commandMock->method('getHelperSet')->willReturn(new HelperSet([]));
        $commandMock->expects($this->atLeast(2))
            ->method('execute')
            ->willReturn(0);

        $bootstrap = new ConsoleBootstrap();

        // Act
        $code = $this->invokeBoostrapCommandMethod(
            [
                $commandMock,
                $inputMock,
                $outputMock,
            ],
        );

        // Assert
        $this->assertSame(Console::CODE_SUCCESS, $code);
    }

    /**
     * A workaround to call a protected/private method of a class.
     *
     * @param array<string, mixed> $methodParameters
     *
     * @return mixed.
     */
    protected function invokeBoostrapCommandMethod(array $methodParameters = [])
    {
        $object = new ConsoleBootstrap();

        $reflection = new ReflectionClassReflectionClass(get_class($object));

        $method = $reflection->getMethod('doRunCommand');
        $method->setAccessible(true);

        return $method->invokeArgs($object, $methodParameters);
    }
}
