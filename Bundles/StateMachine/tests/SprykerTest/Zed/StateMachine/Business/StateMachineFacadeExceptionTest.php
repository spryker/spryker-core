<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StateMachine\Business;

use Codeception\Test\Unit;
use Exception;
use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Spryker\Service\UtilNetwork\UtilNetworkService;
use Spryker\Zed\Graph\Communication\Plugin\GraphPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\StateMachine\Business\Exception\StateMachineException;
use Spryker\Zed\StateMachine\Business\StateMachineBusinessFactory;
use Spryker\Zed\StateMachine\Business\StateMachineFacade;
use Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface;
use Spryker\Zed\StateMachine\StateMachineConfig as SprykerStateMachineConfig;
use Spryker\Zed\StateMachine\StateMachineDependencyProvider;
use SprykerTest\Zed\StateMachine\Mocks\StateMachineConfig;
use SprykerTest\Zed\StateMachine\Mocks\TestStateMachineHandlerException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group StateMachine
 * @group Business
 * @group Facade
 * @group StateMachineFacadeExceptionTest
 * Add your own group annotations below this line
 */
class StateMachineFacadeExceptionTest extends Unit
{
    /**
     * @var string
     */
    public const TESTING_SM = 'TestingSm';

    /**
     * @var string
     */
    public const TEST_PROCESS_NAME = 'TestProcess';

    /**
     * @return void
     */
    public function testGetManualEventsForStateMachineItemShouldReturnAlsoOnEnterEventsForProvidedState(): void
    {
        $processName = static::TEST_PROCESS_NAME;
        $identifier = 1985;

        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setProcessName($processName);
        $stateMachineProcessTransfer->setStateMachineName(static::TESTING_SM);

        $stateMachineHandler = new TestStateMachineHandlerException();
        $stateMachineFacade = $this->createStateMachineFacade($stateMachineHandler);

        $isException = false;

        try {
            $stateMachineFacade->triggerForNewStateMachineItem($stateMachineProcessTransfer, $identifier);
        } catch (Exception $exception) {
            // We simulate real execution
            $isException = true;
        }

        $this->assertTrue($isException, 'Exception not thrown as expected');

        $stateMachineItemTransfer = $stateMachineHandler->getItemStateUpdated();

        $manualEvents = $stateMachineFacade->getManualEventsForStateMachineItem($stateMachineItemTransfer);

        $this->assertSame('invoice created', $stateMachineItemTransfer->getStateName());

        $manualEvent = array_pop($manualEvents);
        $this->assertSame('send invoice', $manualEvent, 'Does not contain the onEnter event.');
    }

    /**
     * @return void
     */
    public function testDrawProcessThrowsExceptionWhenXmlFileDoNotExist(): void
    {
        // Arrange
        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setProcessName('not_existing_process_name');
        $stateMachineProcessTransfer->setStateMachineName(static::TESTING_SM);

        // Assert
        $this->expectException(StateMachineException::class);
        $this->expectExceptionMessage('State machine XML file not found in "vendor/spryker/spryker/Bundles/StateMachine/tests/SprykerTest/Zed/StateMachine/Business/../_support/Fixtures".');

        // Act
        $this->getStateMachineFacadeWithConfigMock()->drawProcess($stateMachineProcessTransfer);
    }

    /**
     * @return void
     */
    public function testDrawProcessThrowsExceptionWhenFileExistsButNotWhitelisted(): void
    {
        // Arrange
        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setProcessName('TestRestricted');
        $stateMachineProcessTransfer->setStateMachineName('../FixturesRestricted');

        // Assert
        $this->expectException(StateMachineException::class);
        $this->expectExceptionMessage('State machine XML file not found in "vendor/spryker/spryker/Bundles/StateMachine/tests/SprykerTest/Zed/StateMachine/Business/../_support/Fixtures".');

        // Act
        $this->getStateMachineFacadeWithConfigMock()->drawProcess($stateMachineProcessTransfer);
    }

    /**
     * @param \Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface $stateMachineHandler
     *
     * @return \Spryker\Zed\StateMachine\Business\StateMachineFacade
     */
    protected function createStateMachineFacade(StateMachineHandlerInterface $stateMachineHandler): StateMachineFacade
    {
        $stateMachineBusinessFactory = new StateMachineBusinessFactory();
        $stateMachineConfig = new StateMachineConfig();
        $stateMachineBusinessFactory->setConfig($stateMachineConfig);

        $container = new Container();
        $container[StateMachineDependencyProvider::PLUGINS_STATE_MACHINE_HANDLERS] = function () use ($stateMachineHandler) {
            return [
               $stateMachineHandler,
            ];
        };

        $container[StateMachineDependencyProvider::PLUGIN_GRAPH] = function () {
            return new GraphPlugin();
        };

        $container[StateMachineDependencyProvider::SERVICE_NETWORK] = function () {
            return new UtilNetworkService();
        };

        $stateMachineBusinessFactory->setContainer($container);

        $stateMachineFacade = new StateMachineFacade();
        $stateMachineFacade->setFactory($stateMachineBusinessFactory);

        return $stateMachineFacade;
    }

    /**
     * @param string $configuredPathToStateMachineXmlFiles
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\StateMachine\StateMachineConfig
     */
    protected function getStateMachineConfigMock(string $configuredPathToStateMachineXmlFiles): SprykerStateMachineConfig
    {
        $stateMachineConfigMock = $this->getMockBuilder(SprykerStateMachineConfig::class)
            ->onlyMethods(['getPathToStateMachineXmlFiles'])
            ->getMock();

        $stateMachineConfigMock
            ->method('getPathToStateMachineXmlFiles')
            ->willReturn($configuredPathToStateMachineXmlFiles);

        return $stateMachineConfigMock;
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\StateMachineFacade
     */
    protected function getStateMachineFacadeWithConfigMock(): StateMachineFacade
    {
        $stateMachineConfigMock = $this->getStateMachineConfigMock(
            __DIR__ . '/../_support/Fixtures',
        );
        $stateMachineBusinessFactory = (new StateMachineBusinessFactory())->setConfig($stateMachineConfigMock);

        return (new StateMachineFacade())->setFactory($stateMachineBusinessFactory);
    }
}
