<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StateMachine\Business;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineEventTimeoutQuery;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineLock;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineLockQuery;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineProcessQuery;
use Spryker\Service\UtilNetwork\UtilNetworkService;
use Spryker\Zed\Graph\Communication\Plugin\GraphPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\StateMachine\Business\StateMachineBusinessFactory;
use Spryker\Zed\StateMachine\Business\StateMachineFacade;
use Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface;
use Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface;
use Spryker\Zed\StateMachine\StateMachineDependencyProvider;
use SprykerTest\Zed\StateMachine\Mocks\StateMachineConfig;
use SprykerTest\Zed\StateMachine\Mocks\TestStateMachineHandler;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group StateMachine
 * @group Business
 * @group Facade
 * @group StateMachineFacadeTest
 * Add your own group annotations below this line
 */
class StateMachineFacadeTest extends Unit
{
    public const TESTING_SM = 'TestingSm';
    public const TEST_PROCESS_NAME = 'TestProcess';
    public const TEST_PROCESS_WITH_LOOP_NAME = 'TestProcessWithLoop';

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
    public function testTriggerForNewStateMachineItemWhenInitialProcessIsSuccessShouldNotifyHandlerStateChange(): void
    {
        $processName = static::TEST_PROCESS_NAME;
        $identifier = 1985;

        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setProcessName($processName);
        $stateMachineProcessTransfer->setStateMachineName(static::TESTING_SM);

        $stateMachineHandler = new TestStateMachineHandler();
        $stateMachineFacade = $this->createStateMachineFacade($stateMachineHandler);

        $triggerResult = $stateMachineFacade->triggerForNewStateMachineItem($stateMachineProcessTransfer, $identifier);

        $stateMachineItemTransfer = $stateMachineHandler->getItemStateUpdated();

        $stateMachineProcessEntity = SpyStateMachineProcessQuery::create()
            ->filterByName($processName)
            ->filterByStateMachineName(static::TESTING_SM)
            ->findOne();

        $stateMachineItemStateEntity = SpyStateMachineItemStateQuery::create()
            ->joinProcess()
            ->filterByFkStateMachineProcess($stateMachineProcessEntity->getIdStateMachineProcess())
            ->filterByName($stateMachineHandler->getInitialStateForProcess($processName))
            ->findOne();

        $this->assertNotEmpty($stateMachineItemStateEntity);
        $this->assertEquals(3, $triggerResult);
        $this->assertEquals($identifier, $stateMachineItemTransfer->getIdentifier());
        $this->assertEquals('order exported', $stateMachineItemTransfer->getStateName());
        $this->assertEquals($processName, $stateMachineItemTransfer->getProcessName());
    }

    /**
     * @return void
     */
    public function testTriggerEventForItemWithManualEventShouldMoveToNextStateWithManualEvent(): void
    {
        $processName = static::TEST_PROCESS_NAME;
        $identifier = 1985;

        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setProcessName($processName);
        $stateMachineProcessTransfer->setStateMachineName(static::TESTING_SM);

        $stateMachineHandler = new TestStateMachineHandler();
        $stateMachineFacade = $this->createStateMachineFacade($stateMachineHandler);

        $stateMachineFacade->triggerForNewStateMachineItem($stateMachineProcessTransfer, $identifier);

        $stateMachineItemTransfer = $stateMachineHandler->getItemStateUpdated();

        $triggerResult = $stateMachineFacade->triggerEvent('ship order', $stateMachineItemTransfer);

        $stateMachineItemTransfer = $stateMachineHandler->getItemStateUpdated();

        $this->assertEquals(2, $triggerResult);
        $this->assertEquals('waiting for payment', $stateMachineItemTransfer->getStateName());
        $this->assertEquals($processName, $stateMachineItemTransfer->getProcessName());
        $this->assertEquals($identifier, $stateMachineItemTransfer->getIdentifier());
    }

    /**
     * @return void
     */
    public function testGetProcessesShouldReturnListOfProcessesAddedToHandler(): void
    {
        $processName = static::TEST_PROCESS_NAME;

        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setProcessName($processName);
        $stateMachineProcessTransfer->setStateMachineName(static::TESTING_SM);

        $stateMachineHandler = new TestStateMachineHandler();
        $stateMachineFacade = $this->createStateMachineFacade($stateMachineHandler);

        $processList = $stateMachineFacade->getProcesses(static::TESTING_SM);

        $this->assertCount(1, $processList);

        /** @var \Generated\Shared\Transfer\StateMachineProcessTransfer $process */
        $process = array_pop($processList);
        $this->assertEquals($processName, $process->getProcessName());
    }

    /**
     * @return void
     */
    public function testGetStateMachineProcessIdShouldReturnIdStoredInPersistence(): void
    {
        $processName = static::TEST_PROCESS_NAME;

        $stateMachineHandler = new TestStateMachineHandler();
        $stateMachineFacade = $this->createStateMachineFacade($stateMachineHandler);

        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setProcessName($processName);
        $stateMachineProcessTransfer->setStateMachineName(static::TESTING_SM);

        $processId = $stateMachineFacade->getStateMachineProcessId($stateMachineProcessTransfer);

        $stateMachineProcessEntity = SpyStateMachineProcessQuery::create()
            ->filterByName($processName)
            ->filterByStateMachineName(static::TESTING_SM)
            ->findOne();

        $this->assertEquals($stateMachineProcessEntity->getIdStateMachineProcess(), $processId);
    }

    /**
     * @return void
     */
    public function testGetManualEventsForStateMachineItemShouldReturnAllManualEventsForProvidedState(): void
    {
        $processName = static::TEST_PROCESS_NAME;
        $identifier = 1985;

        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setProcessName($processName);
        $stateMachineProcessTransfer->setStateMachineName(static::TESTING_SM);

        $stateMachineHandler = new TestStateMachineHandler();
        $stateMachineFacade = $this->createStateMachineFacade($stateMachineHandler);

        $stateMachineFacade->triggerForNewStateMachineItem($stateMachineProcessTransfer, $identifier);

        $stateMachineItemTransfer = $stateMachineHandler->getItemStateUpdated();

        $manualEvents = $stateMachineFacade->getManualEventsForStateMachineItem($stateMachineItemTransfer);

        $this->assertEquals('order exported', $stateMachineItemTransfer->getStateName());
        $this->assertCount(2, $manualEvents);

        $manualEvent = array_pop($manualEvents);
        $this->assertEquals('check with condition', $manualEvent);

        $manualEvent = array_pop($manualEvents);
        $this->assertEquals('ship order', $manualEvent);
    }

    /**
     * @return void
     */
    public function testGetManualEventForStateMachineItemsShouldReturnAllEventsForProvidedStates(): void
    {
        $processName = static::TEST_PROCESS_NAME;
        $firstItemIdentifier = 1985;
        $secondItemIdentifier = 1988;

        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setProcessName($processName);
        $stateMachineProcessTransfer->setStateMachineName(static::TESTING_SM);

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
     * @return void
     */
    public function testGetProcessedStateMachineItemsShouldReturnItemsByProvidedStateIdsStoredInPersistence(): void
    {
        $processName = static::TEST_PROCESS_NAME;
        $firstItemIdentifier = 1985;
        $secondItemIdentifier = 1988;

        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setProcessName($processName);
        $stateMachineProcessTransfer->setStateMachineName(static::TESTING_SM);

        $stateMachineHandler = new TestStateMachineHandler();
        $stateMachineFacade = $this->createStateMachineFacade($stateMachineHandler);

        /** @var \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems */
        $stateMachineItems = [];
        $stateMachineFacade->triggerForNewStateMachineItem($stateMachineProcessTransfer, $firstItemIdentifier);
        $stateMachineItems[] = $stateMachineHandler->getItemStateUpdated();

        $stateMachineFacade->triggerForNewStateMachineItem($stateMachineProcessTransfer, $secondItemIdentifier);
        $stateMachineItems[] = $stateMachineHandler->getItemStateUpdated();

        $stateMachineHandler = new TestStateMachineHandler();
        $stateMachineFacade = $this->createStateMachineFacade($stateMachineHandler);

        /** @var \Generated\Shared\Transfer\StateMachineItemTransfer[] $updatedStateMachineItems */
        $updatedStateMachineItems = $stateMachineFacade->getProcessedStateMachineItems($stateMachineItems);

        $this->assertCount(2, $updatedStateMachineItems);

        $firstUpdatedStateMachineItemTransfer = $updatedStateMachineItems[0];
        $firstBeforeUpdateStateMachineItemTransfer = $stateMachineItems[0];
        $this->assertEquals(
            $firstUpdatedStateMachineItemTransfer->getIdItemState(),
            $firstBeforeUpdateStateMachineItemTransfer->getIdItemState()
        );
        $this->assertEquals(
            $firstUpdatedStateMachineItemTransfer->getProcessName(),
            $firstBeforeUpdateStateMachineItemTransfer->getProcessName()
        );
        $this->assertEquals(
            $firstUpdatedStateMachineItemTransfer->getIdStateMachineProcess(),
            $firstBeforeUpdateStateMachineItemTransfer->getIdStateMachineProcess()
        );
        $this->assertEquals(
            $firstUpdatedStateMachineItemTransfer->getStateName(),
            $firstBeforeUpdateStateMachineItemTransfer->getStateName()
        );
        $this->assertEquals(
            $firstUpdatedStateMachineItemTransfer->getIdentifier(),
            $firstBeforeUpdateStateMachineItemTransfer->getIdentifier()
        );

        $secondUpdatedStateMachineItemTransfer = $updatedStateMachineItems[1];
        $secondBeforeUpdateStateMachineItemTransfer = $stateMachineItems[1];
        $this->assertEquals(
            $secondUpdatedStateMachineItemTransfer->getIdItemState(),
            $secondBeforeUpdateStateMachineItemTransfer->getIdItemState()
        );
        $this->assertEquals(
            $secondUpdatedStateMachineItemTransfer->getProcessName(),
            $secondBeforeUpdateStateMachineItemTransfer->getProcessName()
        );
        $this->assertEquals(
            $secondUpdatedStateMachineItemTransfer->getIdStateMachineProcess(),
            $secondBeforeUpdateStateMachineItemTransfer->getIdStateMachineProcess()
        );
        $this->assertEquals(
            $secondUpdatedStateMachineItemTransfer->getStateName(),
            $secondBeforeUpdateStateMachineItemTransfer->getStateName()
        );
        $this->assertEquals(
            $secondUpdatedStateMachineItemTransfer->getIdentifier(),
            $secondBeforeUpdateStateMachineItemTransfer->getIdentifier()
        );
    }

    /**
     * @return void
     */
    public function testGetProcessedStateMachineItemTransferShouldReturnItemTransfer(): void
    {
        $processName = static::TEST_PROCESS_NAME;
        $firstItemIdentifier = 1985;

        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setProcessName($processName);
        $stateMachineProcessTransfer->setStateMachineName(static::TESTING_SM);

        $stateMachineHandler = new TestStateMachineHandler();
        $stateMachineFacade = $this->createStateMachineFacade($stateMachineHandler);

        $stateMachineFacade->triggerForNewStateMachineItem($stateMachineProcessTransfer, $firstItemIdentifier);
        $stateMachineItemTransfer = $stateMachineHandler->getItemStateUpdated();

        $stateMachineHandler = new TestStateMachineHandler();
        $stateMachineFacade = $this->createStateMachineFacade($stateMachineHandler);

        $updatedStateMachineItemTransfer = $stateMachineFacade
            ->getProcessedStateMachineItemTransfer($stateMachineItemTransfer);

        $this->assertEquals(
            $updatedStateMachineItemTransfer->getIdItemState(),
            $stateMachineItemTransfer->getIdItemState()
        );
        $this->assertEquals(
            $updatedStateMachineItemTransfer->getProcessName(),
            $stateMachineItemTransfer->getProcessName()
        );
        $this->assertEquals(
            $updatedStateMachineItemTransfer->getIdStateMachineProcess(),
            $stateMachineItemTransfer->getIdStateMachineProcess()
        );
        $this->assertEquals(
            $updatedStateMachineItemTransfer->getStateName(),
            $stateMachineItemTransfer->getStateName()
        );
        $this->assertEquals(
            $updatedStateMachineItemTransfer->getIdentifier(),
            $stateMachineItemTransfer->getIdentifier()
        );
    }

    /**
     * @return void
     */
    public function testGetStateHistoryByStateItemIdentifierShouldReturnAllHistoryForThatItem(): void
    {
        $processName = static::TEST_PROCESS_NAME;
        $identifier = 1985;

        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setProcessName($processName);
        $stateMachineProcessTransfer->setStateMachineName(static::TESTING_SM);

        $stateMachineHandler = new TestStateMachineHandler();
        $stateMachineFacade = $this->createStateMachineFacade($stateMachineHandler);

        $stateMachineFacade->triggerForNewStateMachineItem($stateMachineProcessTransfer, $identifier);
        $stateMachineItemTransfer = $stateMachineHandler->getItemStateUpdated();

        $stateMachineItemsTransfer = $stateMachineFacade->getStateHistoryByStateItemIdentifier(
            $stateMachineItemTransfer->getIdStateMachineProcess(),
            $identifier
        );

        $this->assertCount(3, $stateMachineItemsTransfer);

        $stateMachineItemTransfer = $stateMachineItemsTransfer[0];
        $this->assertEquals('invoice created', $stateMachineItemTransfer->getStateName());

        $stateMachineItemTransfer = $stateMachineItemsTransfer[1];
        $this->assertEquals('invoice sent', $stateMachineItemTransfer->getStateName());

        $stateMachineItemTransfer = $stateMachineItemsTransfer[2];
        $this->assertEquals('order exported', $stateMachineItemTransfer->getStateName());
    }

    /**
     * @return void
     */
    public function testGetItemsWithFlagShouldReturnListOfStateMachineItemsWithGivenFlag(): void
    {
        $processName = static::TEST_PROCESS_NAME;
        $identifier = 1985;

        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setProcessName($processName);
        $stateMachineProcessTransfer->setStateMachineName(static::TESTING_SM);

        $stateMachineHandler = new TestStateMachineHandler();
        $stateMachineFacade = $this->createStateMachineFacade($stateMachineHandler);

        $stateMachineFacade->triggerForNewStateMachineItem($stateMachineProcessTransfer, $identifier);

        $stateMachineItemsWithGivenFlag = $stateMachineFacade->getItemsWithFlag(
            $stateMachineProcessTransfer,
            'Flag1'
        );

        $this->assertCount(2, $stateMachineItemsWithGivenFlag);

        $this->assertContainsOnlyInstancesOf(StateMachineItemTransfer::class, $stateMachineItemsWithGivenFlag);
        $stateMachineItemTransfer = $stateMachineItemsWithGivenFlag[0];
        $this->assertEquals('invoice created', $stateMachineItemTransfer->getStateName());
        $this->assertEquals($identifier, $stateMachineItemTransfer->getIdentifier());

        $stateMachineItemTransfer = $stateMachineItemsWithGivenFlag[1];
        $this->assertEquals('invoice sent', $stateMachineItemTransfer->getStateName());
        $this->assertEquals($identifier, $stateMachineItemTransfer->getIdentifier());

        $stateMachineItemsWithGivenFlag = $stateMachineFacade->getItemsWithFlag(
            $stateMachineProcessTransfer,
            'Flag2'
        );

        $this->assertCount(1, $stateMachineItemsWithGivenFlag);
    }

    /**
     * @return void
     */
    public function testGetItemsWithFlagShouldReturnSortedListOfStateMachineItemsWithGivenFlag(): void
    {
        $processName = static::TEST_PROCESS_NAME;
        $identifier = 1985;
        $identifier2 = 1986;

        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setProcessName($processName);
        $stateMachineProcessTransfer->setStateMachineName(static::TESTING_SM);

        $stateMachineHandler = new TestStateMachineHandler();
        $stateMachineFacade = $this->createStateMachineFacade($stateMachineHandler);

        $stateMachineFacade->triggerForNewStateMachineItem($stateMachineProcessTransfer, $identifier);
        sleep(1);
        $stateMachineFacade->triggerForNewStateMachineItem($stateMachineProcessTransfer, $identifier2);

        $stateMachineItemsWithGivenFlag = $stateMachineFacade->getItemsWithFlag(
            $stateMachineProcessTransfer,
            'Flag1'
        );

        foreach ($stateMachineItemsWithGivenFlag as $stateMachineItemTransfer) {
            $this->assertEquals($identifier, $stateMachineItemTransfer->getIdentifier());
        }

        $stateMachineItemsWithGivenFlag = $stateMachineFacade->getItemsWithFlag(
            $stateMachineProcessTransfer,
            'Flag1',
            'DESC'
        );

        foreach ($stateMachineItemsWithGivenFlag as $stateMachineItemTransfer) {
            $this->assertEquals($identifier2, $stateMachineItemTransfer->getIdentifier());
        }
    }

    /**
     * @return void
     */
    public function testGetItemsWithoutFlagShouldReturnListOfStateMachineItemsWithoutGivenFlag(): void
    {
        $processName = static::TEST_PROCESS_NAME;
        $identifier = 1985;

        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setProcessName($processName);
        $stateMachineProcessTransfer->setStateMachineName(static::TESTING_SM);

        $stateMachineHandler = new TestStateMachineHandler();
        $stateMachineFacade = $this->createStateMachineFacade($stateMachineHandler);

        $stateMachineFacade->triggerForNewStateMachineItem($stateMachineProcessTransfer, $identifier);

        $stateMachineItemsWithoutGivenFlag = $stateMachineFacade->getItemsWithoutFlag(
            $stateMachineProcessTransfer,
            'Flag1'
        );

        $this->assertCount(1, $stateMachineItemsWithoutGivenFlag);

        $stateMachineItemTransfer = $stateMachineItemsWithoutGivenFlag[0];
        $this->assertInstanceOf(StateMachineItemTransfer::class, $stateMachineItemTransfer);
        $this->assertEquals('order exported', $stateMachineItemTransfer->getStateName());
        $this->assertEquals($identifier, $stateMachineItemTransfer->getIdentifier());

        $stateMachineItemsWithoutGivenFlag = $stateMachineFacade->getItemsWithoutFlag(
            $stateMachineProcessTransfer,
            'Flag2'
        );

        $stateMachineItemTransfer = $stateMachineItemsWithoutGivenFlag[0];
        $this->assertEquals('invoice created', $stateMachineItemTransfer->getStateName());
        $this->assertEquals($identifier, $stateMachineItemTransfer->getIdentifier());

        $stateMachineItemTransfer = $stateMachineItemsWithoutGivenFlag[1];
        $this->assertEquals('order exported', $stateMachineItemTransfer->getStateName());
        $this->assertEquals($identifier, $stateMachineItemTransfer->getIdentifier());

        $this->assertCount(2, $stateMachineItemsWithoutGivenFlag);
    }

    /**
     * @return void
     */
    public function testCheckConditionsShouldProcessStatesWithConditionAndWithoutEvent(): void
    {
        $processName = static::TEST_PROCESS_NAME;
        $identifier = 1985;

        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setProcessName($processName);
        $stateMachineProcessTransfer->setStateMachineName(static::TESTING_SM);

        $stateMachineHandler = new TestStateMachineHandler();
        $stateMachineFacade = $this->createStateMachineFacade($stateMachineHandler);

        $stateMachineFacade->triggerForNewStateMachineItem($stateMachineProcessTransfer, $identifier);

        $stateMachineItemTransfer = $stateMachineHandler->getItemStateUpdated();

        $stateMachineFacade->triggerEvent('check with condition', $stateMachineItemTransfer);

        $stateMachineHandler->setStateMachineItemsByStateIds([$stateMachineItemTransfer]);

        $stateMachineFacade->checkConditions(static::TESTING_SM);

        $stateMachineItemTransfer = $stateMachineHandler->getItemStateUpdated();

        $this->assertEquals('waiting for payment', $stateMachineItemTransfer->getStateName());
    }

    /**
     * @return void
     */
    public function testCheckTimeoutsShouldMoveStatesWithExpiredTimeouts(): void
    {
        $processName = static::TEST_PROCESS_NAME;
        $identifier = 1985;

        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setProcessName($processName);
        $stateMachineProcessTransfer->setStateMachineName(static::TESTING_SM);

        $stateMachineHandler = new TestStateMachineHandler();
        $stateMachineFacade = $this->createStateMachineFacade($stateMachineHandler);

        $stateMachineFacade->triggerForNewStateMachineItem($stateMachineProcessTransfer, $identifier);

        $stateMachineItemTransfer = $stateMachineHandler->getItemStateUpdated();

        $stateMachineFacade->triggerEvent('ship order', $stateMachineItemTransfer);

        $stateMachineItemTransfer = $stateMachineHandler->getItemStateUpdated();

        $stateMachineItemEventTimeoutEntity = SpyStateMachineEventTimeoutQuery::create()
            ->filterByIdentifier($stateMachineItemTransfer->getIdentifier())
            ->filterByFkStateMachineProcess($stateMachineItemTransfer->getIdStateMachineProcess())
            ->findOne();

        $stateMachineItemEventTimeoutEntity->setTimeout('1985-07-01');
        $stateMachineItemEventTimeoutEntity->save();

        $affectedItems = $stateMachineFacade->checkTimeouts(static::TESTING_SM);

        $stateMachineItemTransfer = $stateMachineHandler->getItemStateUpdated();

        $this->assertEquals(1, $affectedItems);
        $this->assertEquals('reminder I sent', $stateMachineItemTransfer->getStateName());
    }

    /**
     * @return void
     */
    public function testClearLocksShouldEmptyDatabaseFromExpiredLocks(): void
    {
        $identifier = '1985-07-01';
        $stateMachineHandler = new TestStateMachineHandler();
        $stateMachineFacade = $this->createStateMachineFacade($stateMachineHandler);

        $stateMachineLockEntity = new SpyStateMachineLock();
        $stateMachineLockEntity->setIdentifier($identifier);
        $stateMachineLockEntity->setExpires(new DateTime('Yesterday'));
        $stateMachineLockEntity->save();

        $stateMachineFacade->clearLocks();

        $numberOfItems = SpyStateMachineLockQuery::create()->filterByIdentifier($identifier)->count();

        $this->assertEquals(0, $numberOfItems);
    }

    /**
     * @return void
     */
    public function testLoopDoesNotCauseExceptions(): void
    {
        $processName = static::TEST_PROCESS_WITH_LOOP_NAME;
        $identifier = 1985;

        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setProcessName($processName);
        $stateMachineProcessTransfer->setStateMachineName(static::TESTING_SM);

        $stateMachineHandler = new TestStateMachineHandler();
        $stateMachineFacade = $this->createStateMachineFacade($stateMachineHandler);

        $stateMachineFacade->triggerForNewStateMachineItem($stateMachineProcessTransfer, $identifier);

        $stateMachineItemTransfer = $stateMachineHandler->getItemStateUpdated();

        $stateMachineFacade->triggerEvent('enter loop action', $stateMachineItemTransfer);
        $triggerResult = $stateMachineFacade->triggerEvent('loop exit action', $stateMachineItemTransfer);

        $stateMachineItemTransfer = $stateMachineHandler->getItemStateUpdated();

        $this->assertEquals(1, $triggerResult);
        $this->assertEquals('done', $stateMachineItemTransfer->getStateName());
        $this->assertEquals($processName, $stateMachineItemTransfer->getProcessName());
        $this->assertEquals($identifier, $stateMachineItemTransfer->getIdentifier());
    }

    /**
     * @return void
     */
    public function testStateMachineExistsReturnsTrueWhenStateMachineHasHandler(): void
    {
        // Assign
        $stateMachineHandler = new TestStateMachineHandler();
        $stateMachineName = $stateMachineHandler->getStateMachineName();
        $stateMachineFacade = $this->createStateMachineFacade($stateMachineHandler);
        $expectedResult = true;

        // Act
        $actualResult = $stateMachineFacade->stateMachineExists($stateMachineName);

        // Assert
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return void
     */
    public function testStateMachineExistsReturnsFalseWhenStateMachineHasNoHandler(): void
    {
        // Assign
        $stateMachineHandler = new TestStateMachineHandler();
        $stateMachineName = $stateMachineHandler->getStateMachineName() . "SomethingElse";
        $stateMachineFacade = $this->createStateMachineFacade($stateMachineHandler);
        $expectedResult = false;

        // Act
        $actualResult = $stateMachineFacade->stateMachineExists($stateMachineName);

        // Assert
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @param \Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface $stateMachineHandler
     *
     * @return \Spryker\Zed\StateMachine\Business\StateMachineFacade
     */
    protected function createStateMachineFacade(StateMachineHandlerInterface $stateMachineHandler): StateMachineFacadeInterface
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
}
