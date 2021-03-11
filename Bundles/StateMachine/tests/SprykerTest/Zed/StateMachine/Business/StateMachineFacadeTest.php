<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StateMachine\Business;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use Generated\Shared\Transfer\StateMachineProcessCriteriaTransfer;
use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineEventTimeoutQuery;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineLock;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineLockQuery;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineProcessQuery;
use Spryker\Service\UtilNetwork\UtilNetworkService;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Graph\Communication\Plugin\GraphPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Propel\PropelConfig;
use Spryker\Zed\StateMachine\Business\StateMachineBusinessFactory;
use Spryker\Zed\StateMachine\Business\StateMachineFacade;
use Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface;
use Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface;
use Spryker\Zed\StateMachine\StateMachineDependencyProvider;
use SprykerTest\Zed\StateMachine\Mocks\StateMachineConfig;
use SprykerTest\Zed\StateMachine\Mocks\TestStateMachineHandler;

/**
 * Auto-generated group annotations
 *
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
    public const TEST_PROCESS_WITHOUT_EVENTS_NAME = 'TestProcessWithoutEvent';
    public const TEST_PROCESS_WITH_LOOP_NAME = 'TestProcessWithLoop';
    public const TEST_NOT_EXISTING_STATE_MACHINE_PROCESS_ID = 0;

    /**
     * @var \SprykerTest\Zed\StateMachine\StateMachineBusinessTester
     */
    protected $tester;

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
        $this->assertSame(3, $triggerResult);
        $this->assertSame($identifier, $stateMachineItemTransfer->getIdentifier());
        $this->assertSame('order exported', $stateMachineItemTransfer->getStateName());
        $this->assertSame($processName, $stateMachineItemTransfer->getProcessName());
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

        $this->assertSame(2, $triggerResult);
        $this->assertSame('waiting for payment', $stateMachineItemTransfer->getStateName());
        $this->assertSame($processName, $stateMachineItemTransfer->getProcessName());
        $this->assertSame($identifier, $stateMachineItemTransfer->getIdentifier());
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

        $this->assertCount(2, $processList);

        /** @var \Generated\Shared\Transfer\StateMachineProcessTransfer $process */
        $process = current($processList);
        $this->assertSame(static::TEST_PROCESS_NAME, $process->getProcessName());
        $process = array_pop($processList);
        $this->assertSame(static::TEST_PROCESS_WITHOUT_EVENTS_NAME, $process->getProcessName());
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

        $this->assertSame($stateMachineProcessEntity->getIdStateMachineProcess(), $processId);
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

        $this->assertSame('order exported', $stateMachineItemTransfer->getStateName());
        $this->assertCount(2, $manualEvents);

        $manualEvent = array_pop($manualEvents);
        $this->assertSame('check with condition', $manualEvent);

        $manualEvent = array_pop($manualEvents);
        $this->assertSame('ship order', $manualEvent);
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

        $this->sleepIfMySql(1);

        $stateMachineFacade->triggerForNewStateMachineItem($stateMachineProcessTransfer, $secondItemIdentifier);
        $stateMachineItems[$secondItemIdentifier] = $stateMachineHandler->getItemStateUpdated();

        $this->sleepIfMySql(1);

        $stateMachineFacade->triggerEvent('ship order', $stateMachineItems[$secondItemIdentifier]);

        $manualEvents = $stateMachineFacade->getManualEventsForStateMachineItems($stateMachineItems);

        $this->assertCount(2, $manualEvents);

        $firstItemManualEvents = $manualEvents[$firstItemIdentifier];
        $secondItemManualEvents = $manualEvents[$secondItemIdentifier];

        $manualEvent = array_pop($firstItemManualEvents);
        $this->assertSame('check with condition', $manualEvent);

        $manualEvent = array_pop($firstItemManualEvents);
        $this->assertSame('ship order', $manualEvent);

        $manualEvent = array_pop($secondItemManualEvents);
        $this->assertSame('payment received', $manualEvent);
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

        $this->sleepIfMySql(1);

        $stateMachineFacade->triggerForNewStateMachineItem($stateMachineProcessTransfer, $secondItemIdentifier);
        $stateMachineItems[] = $stateMachineHandler->getItemStateUpdated();

        $stateMachineHandler = new TestStateMachineHandler();
        $stateMachineFacade = $this->createStateMachineFacade($stateMachineHandler);

        /** @var \Generated\Shared\Transfer\StateMachineItemTransfer[] $updatedStateMachineItems */
        $updatedStateMachineItems = $stateMachineFacade->getProcessedStateMachineItems($stateMachineItems);

        $this->assertCount(2, $updatedStateMachineItems);

        $firstUpdatedStateMachineItemTransfer = $updatedStateMachineItems[0];
        $firstBeforeUpdateStateMachineItemTransfer = $stateMachineItems[0];
        $this->assertSame(
            $firstUpdatedStateMachineItemTransfer->getIdItemState(),
            $firstBeforeUpdateStateMachineItemTransfer->getIdItemState()
        );
        $this->assertSame(
            $firstUpdatedStateMachineItemTransfer->getProcessName(),
            $firstBeforeUpdateStateMachineItemTransfer->getProcessName()
        );
        $this->assertSame(
            $firstUpdatedStateMachineItemTransfer->getIdStateMachineProcess(),
            $firstBeforeUpdateStateMachineItemTransfer->getIdStateMachineProcess()
        );
        $this->assertSame(
            $firstUpdatedStateMachineItemTransfer->getStateName(),
            $firstBeforeUpdateStateMachineItemTransfer->getStateName()
        );
        $this->assertSame(
            $firstUpdatedStateMachineItemTransfer->getIdentifier(),
            $firstBeforeUpdateStateMachineItemTransfer->getIdentifier()
        );

        $secondUpdatedStateMachineItemTransfer = $updatedStateMachineItems[1];
        $secondBeforeUpdateStateMachineItemTransfer = $stateMachineItems[1];
        $this->assertSame(
            $secondUpdatedStateMachineItemTransfer->getIdItemState(),
            $secondBeforeUpdateStateMachineItemTransfer->getIdItemState()
        );
        $this->assertSame(
            $secondUpdatedStateMachineItemTransfer->getProcessName(),
            $secondBeforeUpdateStateMachineItemTransfer->getProcessName()
        );
        $this->assertSame(
            $secondUpdatedStateMachineItemTransfer->getIdStateMachineProcess(),
            $secondBeforeUpdateStateMachineItemTransfer->getIdStateMachineProcess()
        );
        $this->assertSame(
            $secondUpdatedStateMachineItemTransfer->getStateName(),
            $secondBeforeUpdateStateMachineItemTransfer->getStateName()
        );
        $this->assertSame(
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

        $this->assertSame(
            $updatedStateMachineItemTransfer->getIdItemState(),
            $stateMachineItemTransfer->getIdItemState()
        );
        $this->assertSame(
            $updatedStateMachineItemTransfer->getProcessName(),
            $stateMachineItemTransfer->getProcessName()
        );
        $this->assertSame(
            $updatedStateMachineItemTransfer->getIdStateMachineProcess(),
            $stateMachineItemTransfer->getIdStateMachineProcess()
        );
        $this->assertSame(
            $updatedStateMachineItemTransfer->getStateName(),
            $stateMachineItemTransfer->getStateName()
        );
        $this->assertSame(
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
        $this->sleepIfMySql(1);
        $stateMachineItemTransfer = $stateMachineHandler->getItemStateUpdated();

        $stateMachineItemTransfers = $stateMachineFacade->getStateHistoryByStateItemIdentifier(
            $stateMachineItemTransfer->getIdStateMachineProcess(),
            $identifier
        );

        $this->assertCount(3, $stateMachineItemTransfers);

        $stateMachineItemTransfer = array_shift($stateMachineItemTransfers);
        $this->assertSame('invoice created', $stateMachineItemTransfer->getStateName());

        $stateMachineItemTransfer = array_shift($stateMachineItemTransfers);
        $this->assertSame('invoice sent', $stateMachineItemTransfer->getStateName());

        $stateMachineItemTransfer = array_shift($stateMachineItemTransfers);
        $this->assertSame('order exported', $stateMachineItemTransfer->getStateName());
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

        $this->sleepIfMySql(1);

        $stateMachineItemsWithGivenFlag = $stateMachineFacade->getItemsWithFlag(
            $stateMachineProcessTransfer,
            'Flag1'
        );

        $this->assertCount(2, $stateMachineItemsWithGivenFlag);

        $this->assertContainsOnlyInstancesOf(StateMachineItemTransfer::class, $stateMachineItemsWithGivenFlag);
        $stateMachineItemTransfer = $stateMachineItemsWithGivenFlag[0];
        $this->assertSame('invoice created', $stateMachineItemTransfer->getStateName());
        $this->assertSame($identifier, $stateMachineItemTransfer->getIdentifier());

        $stateMachineItemTransfer = $stateMachineItemsWithGivenFlag[1];
        $this->assertSame('invoice sent', $stateMachineItemTransfer->getStateName());
        $this->assertSame($identifier, $stateMachineItemTransfer->getIdentifier());

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
        $this->sleepIfMySql(1);
        $stateMachineFacade->triggerForNewStateMachineItem($stateMachineProcessTransfer, $identifier2);

        $stateMachineItemsWithGivenFlag = $stateMachineFacade->getItemsWithFlag(
            $stateMachineProcessTransfer,
            'Flag1'
        );

        foreach ($stateMachineItemsWithGivenFlag as $stateMachineItemTransfer) {
            $this->assertSame($identifier, $stateMachineItemTransfer->getIdentifier());
        }

        $stateMachineItemsWithGivenFlag = $stateMachineFacade->getItemsWithFlag(
            $stateMachineProcessTransfer,
            'Flag1',
            'DESC'
        );

        foreach ($stateMachineItemsWithGivenFlag as $stateMachineItemTransfer) {
            $this->assertSame($identifier2, $stateMachineItemTransfer->getIdentifier());
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
        $this->sleepIfMySql(1);

        $stateMachineItemsWithoutGivenFlag = $stateMachineFacade->getItemsWithoutFlag(
            $stateMachineProcessTransfer,
            'Flag1'
        );

        $this->assertCount(1, $stateMachineItemsWithoutGivenFlag);

        $stateMachineItemTransfer = $stateMachineItemsWithoutGivenFlag[0];
        $this->assertInstanceOf(StateMachineItemTransfer::class, $stateMachineItemTransfer);
        $this->assertSame('order exported', $stateMachineItemTransfer->getStateName());
        $this->assertSame($identifier, $stateMachineItemTransfer->getIdentifier());

        $stateMachineItemsWithoutGivenFlag = $stateMachineFacade->getItemsWithoutFlag(
            $stateMachineProcessTransfer,
            'Flag2'
        );

        $stateMachineItemTransfer = $stateMachineItemsWithoutGivenFlag[0];
        $this->assertSame('invoice created', $stateMachineItemTransfer->getStateName());
        $this->assertSame($identifier, $stateMachineItemTransfer->getIdentifier());

        $stateMachineItemTransfer = $stateMachineItemsWithoutGivenFlag[1];
        $this->assertSame('order exported', $stateMachineItemTransfer->getStateName());
        $this->assertSame($identifier, $stateMachineItemTransfer->getIdentifier());

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

        $stateMachineHandler->setStateMachineItems([$stateMachineItemTransfer]);

        $stateMachineFacade->checkConditions(static::TESTING_SM);

        $stateMachineItemTransfer = $stateMachineHandler->getItemStateUpdated();

        $this->assertSame('waiting for payment', $stateMachineItemTransfer->getStateName());
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

        $this->assertSame(1, $affectedItems);
        $this->assertSame('reminder I sent', $stateMachineItemTransfer->getStateName());
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

        $this->assertSame(0, $numberOfItems);
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

        $this->assertSame(1, $triggerResult);
        $this->assertSame('done', $stateMachineItemTransfer->getStateName());
        $this->assertSame($processName, $stateMachineItemTransfer->getProcessName());
        $this->assertSame($identifier, $stateMachineItemTransfer->getIdentifier());
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
        $stateMachineName = $stateMachineHandler->getStateMachineName() . 'SomethingElse';
        $stateMachineFacade = $this->createStateMachineFacade($stateMachineHandler);
        $expectedResult = false;

        // Act
        $actualResult = $stateMachineFacade->stateMachineExists($stateMachineName);

        // Assert
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return void
     */
    public function testFindStateMachineProcessReturnsCorrectData(): void
    {
        // Arrange
        $stateMachineProcessEntity = $this->tester->haveStateMachineProcess();
        $stateMachineProcessCriteriaTransfer = (new StateMachineProcessCriteriaTransfer())
            ->setIdStateMachineProcess($stateMachineProcessEntity->getIdStateMachineProcess());

        $stateMachineHandler = new TestStateMachineHandler();
        $stateMachineFacade = $this->createStateMachineFacade($stateMachineHandler);

        // Act
        $stateMachineProcessTransfer = $stateMachineFacade->findStateMachineProcess($stateMachineProcessCriteriaTransfer);

        // Assert
        $this->assertNotNull($stateMachineProcessTransfer);
        $this->assertSame($stateMachineProcessTransfer->getProcessName(), $stateMachineProcessEntity->getName());
    }

    /**
     * @return void
     */
    public function testFindStateMachineProcessReturnsNullWithIncorrectFilter(): void
    {
        // Arrange
        $stateMachineProcessCriteriaTransfer = (new StateMachineProcessCriteriaTransfer())
            ->setIdStateMachineProcess(static::TEST_NOT_EXISTING_STATE_MACHINE_PROCESS_ID);
        $stateMachineHandler = new TestStateMachineHandler();
        $stateMachineFacade = $this->createStateMachineFacade($stateMachineHandler);

        // Act
        $stateMachineProcessTransfer = $stateMachineFacade->findStateMachineProcess($stateMachineProcessCriteriaTransfer);

        // Assert
        $this->assertNull($stateMachineProcessTransfer);
    }

    /**
     * @return void
     */
    public function testGetProcessStateNamesReturnsArrayOfStateNames(): void
    {
        // Arrange
        $processName = static::TEST_PROCESS_NAME;
        $identifier = 1985;

        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setProcessName($processName);
        $stateMachineProcessTransfer->setStateMachineName(static::TESTING_SM);

        $stateMachineHandler = new TestStateMachineHandler();
        $stateMachineFacade = $this->createStateMachineFacade($stateMachineHandler);

        $stateMachineFacade->triggerForNewStateMachineItem($stateMachineProcessTransfer, $identifier);

        // Act
        $stateNames = $stateMachineFacade->getProcessStateNames($stateMachineProcessTransfer);

        // Assert
        $this->assertSame('completed', array_pop($stateNames));
        $this->assertSame('state with condition', array_pop($stateNames));
        $this->assertSame('new', array_shift($stateNames));
    }

    /**
     * @return void
     */
    public function testCheckConditionsShouldProcessStatesWithoutConditionAndWithoutEvent(): void
    {
        $processName = static::TEST_PROCESS_WITHOUT_EVENTS_NAME;
        $identifier = 1985;

        $stateMachineProcessEntity = $this->tester->haveStateMachineProcess([
            'stateMachineName' => static::TESTING_SM,
            'processName' => $processName,
        ]);

        $this->tester->haveStateMachineItemState([
            'name' => 'new',
            'fkStateMachineProcess' => $stateMachineProcessEntity->getIdStateMachineProcess(),
        ]);

        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setProcessName($processName);
        $stateMachineProcessTransfer->setStateMachineName(static::TESTING_SM);

        $stateMachineHandler = new TestStateMachineHandler();
        $stateMachineFacade = $this->createStateMachineFacade($stateMachineHandler);

        $stateMachineFacade->triggerForNewStateMachineItem($stateMachineProcessTransfer, $identifier);
        $stateMachineItemTransfer = $stateMachineHandler->getItemStateUpdated();

        $stateMachineHandler->setStateMachineItems([$stateMachineItemTransfer]);

        $stateMachineFacade->checkConditions(static::TESTING_SM);

        $stateMachineItemTransfer = $stateMachineHandler->getItemStateUpdated();

        $this->assertSame('ready', $stateMachineItemTransfer->getStateName());
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

    /**
     * @param int $seconds
     *
     * @return void
     */
    protected function sleepIfMySql(int $seconds): void
    {
        if (Config::get(PropelConstants::ZED_DB_ENGINE) === PropelConfig::DB_ENGINE_MYSQL) {
            sleep($seconds);
        }
    }
}
