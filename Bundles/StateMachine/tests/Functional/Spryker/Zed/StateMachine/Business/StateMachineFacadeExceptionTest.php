<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\StateMachine\Business;

use Codeception\TestCase\Test;
use Functional\Spryker\Zed\StateMachine\Mocks\StateMachineConfig;
use Functional\Spryker\Zed\StateMachine\Mocks\TestStateMachineHandler;
use Functional\Spryker\Zed\StateMachine\Mocks\TestStateMachineHandlerException;
use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Spryker\Zed\Graph\Communication\Plugin\GraphPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\StateMachine\Business\StateMachineBusinessFactory;
use Spryker\Zed\StateMachine\Business\StateMachineFacade;
use Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface;
use Spryker\Zed\StateMachine\StateMachineDependencyProvider;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group StateMachine
 * @group Business
 * @group StateMachineFacadeExceptionTest
 */
class StateMachineFacadeExceptionTest extends Test
{

    const TESTING_SM = 'TestingSm';
    const TEST_PROCESS_NAME = 'TestProcess';

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testGetManualEventsForStateMachineItemShouldReturnAlsoOnEnterEventsForProvidedState()
    {
        $processName = self::TEST_PROCESS_NAME;
        $identifier = 1985;

        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setProcessName($processName);
        $stateMachineProcessTransfer->setStateMachineName(self::TESTING_SM);

        $stateMachineHandler = new TestStateMachineHandlerException();
        $stateMachineFacade = $this->createStateMachineFacade($stateMachineHandler);

        $isException = false;

        try {
            $stateMachineFacade->triggerForNewStateMachineItem($stateMachineProcessTransfer, $identifier);
        } catch (\Exception $exception) {
            // We simulate real execution
            $isException = true;
        }

        $this->assertTrue($isException, 'Exception not thrown as expected');

        $stateMachineItemTransfer = $stateMachineHandler->getItemStateUpdated();

        $manualEvents = $stateMachineFacade->getManualEventsForStateMachineItem($stateMachineItemTransfer);

        $this->assertEquals('invoice created', $stateMachineItemTransfer->getStateName());

        $manualEvent = array_pop($manualEvents);
        $this->assertSame('send invoice', $manualEvent, 'Does not contain the onEnter event.');
    }

    /**
     * @return void
     */
    public function testGetManualEventForStateMachineItemsShouldReturnAllEventsForProvidedStates()
    {
        $processName = self::TEST_PROCESS_NAME;
        $firstItemIdentifier = 1985;
        $secondItemIdentifier = 1988;

        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setProcessName($processName);
        $stateMachineProcessTransfer->setStateMachineName(self::TESTING_SM);

        $stateMachineHandler = new TestStateMachineHandler();
        $stateMachineFacade = $this->createStateMachineFacade($stateMachineHandler);

        $stateMachineItems = [];
        $stateMachineFacade->triggerForNewStateMachineItem($stateMachineProcessTransfer, $firstItemIdentifier);
        $stateMachineItems[$firstItemIdentifier] = $stateMachineHandler->getItemStateUpdated();

        $stateMachineFacade->triggerForNewStateMachineItem($stateMachineProcessTransfer, $secondItemIdentifier);
        $stateMachineItems[$secondItemIdentifier] = $stateMachineHandler->getItemStateUpdated();

        $stateMachineFacade->triggerEvent('ship order', $stateMachineItems[$secondItemIdentifier]);

        $manualEvents = $stateMachineFacade->getManualEventsForStateMachineItems($stateMachineItems);

        $this->assertCount(2, $manualEvents);

        $firstItemManualEvents = $manualEvents[$firstItemIdentifier];
        $secondItemManualEvents = $manualEvents[$secondItemIdentifier];

        $manualEvent = array_pop($firstItemManualEvents);
        $this->assertEquals('check with condition', $manualEvent);

        $manualEvent = array_pop($firstItemManualEvents);
        $this->assertEquals('ship order', $manualEvent);

        $manualEvent = array_pop($secondItemManualEvents);
        $this->assertEquals('payment received', $manualEvent);
    }

    /**

    /**
     *
     * @param \Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface $stateMachineHandler
     *
     * @return \Spryker\Zed\StateMachine\Business\StateMachineFacade
     */
    protected function createStateMachineFacade(StateMachineHandlerInterface $stateMachineHandler)
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

        $stateMachineBusinessFactory->setContainer($container);

        $stateMachineFacade = new StateMachineFacade();
        $stateMachineFacade->setFactory($stateMachineBusinessFactory);

        return $stateMachineFacade;
    }

}
