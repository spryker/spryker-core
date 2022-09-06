<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business\OrderStateMachine;

use Codeception\Test\Unit;
use Exception;
use Generated\Shared\Transfer\OmsCheckConditionsQueryCriteriaTransfer;
use Generated\Shared\Transfer\OmsEventTriggerResponseTransfer;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemState;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcess;
use Orm\Zed\Sales\Persistence\SpySalesOrder as ChildSpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use ReflectionClass;
use Spryker\Zed\Oms\Business\OmsBusinessFactory;
use Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface;
use Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachine;
use Spryker\Zed\Oms\Business\OrderStateMachine\TimeoutInterface;
use Spryker\Zed\Oms\Business\Process\EventInterface;
use Spryker\Zed\Oms\Business\Process\ProcessInterface;
use Spryker\Zed\Oms\Business\Process\State;
use Spryker\Zed\Oms\Business\Process\StateInterface;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Business\Util\ReservationInterface;
use Spryker\Zed\Oms\Business\Util\TransitionLogInterface;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandCollection;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandCollectionInterface;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionCollection;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandInterface;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionCollectionInterface;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;
use Spryker\Zed\Oms\OmsConfig;
use Spryker\Zed\Oms\OmsDependencyProvider;
use Spryker\Zed\Oms\Persistence\OmsQueryContainer;
use Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Business
 * @group OrderStateMachine
 * @group OrderStateMachineTest
 * Add your own group annotations below this line
 */
class OrderStateMachineTest extends Unit
{
    /**
     * @var string
     */
    public const CONDITION_NAME = 'conditionName';

    /**
     * @var string
     */
    public const COMMAND_NAME = 'commandName';

    /**
     * @var \SprykerTest\Zed\Oms\OmsBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testInstantiationConditionsArrayShouldConvertedToCollection(): void
    {
        $orderStateMachine = new OrderStateMachine(
            $this->getQueryContainerMock(),
            $this->getBuilderMock(),
            $this->getTransitionLogMock(),
            $this->getTimeoutMock(),
            new ReadOnlyArrayObject(),
            [static::CONDITION_NAME => $this->getConditionMock()],
            [],
            $this->getReservationMock(),
            new OmsConfig(),
        );
        $reflection = new ReflectionClass(OrderStateMachine::class);
        $reflectionProperty = $reflection->getProperty('conditions');
        $reflectionProperty->setAccessible(true);
        $conditions = $reflectionProperty->getValue($orderStateMachine);

        $this->assertInstanceOf(ConditionCollectionInterface::class, $conditions);
        $this->assertInstanceOf(ConditionInterface::class, $conditions->get(static::CONDITION_NAME));
    }

    /**
     * @return void
     */
    public function testInstantiationWithConditionCollection(): void
    {
        $conditionCollection = new ConditionCollection();
        $conditionCollection->add($this->getConditionMock(), static::CONDITION_NAME);

        $orderStateMachine = new OrderStateMachine(
            $this->getQueryContainerMock(),
            $this->getBuilderMock(),
            $this->getTransitionLogMock(),
            $this->getTimeoutMock(),
            new ReadOnlyArrayObject(),
            $conditionCollection,
            [],
            $this->getReservationMock(),
            new OmsConfig(),
        );
        $reflection = new ReflectionClass(OrderStateMachine::class);
        $reflectionProperty = $reflection->getProperty('conditions');
        $reflectionProperty->setAccessible(true);
        $conditions = $reflectionProperty->getValue($orderStateMachine);

        $this->assertInstanceOf(ConditionCollectionInterface::class, $conditions);
        $this->assertInstanceOf(ConditionInterface::class, $conditions->get(static::CONDITION_NAME));
    }

    /**
     * @return void
     */
    public function testInstantiationCommandsArrayShouldConvertedToCollection(): void
    {
        $orderStateMachine = new OrderStateMachine(
            $this->getQueryContainerMock(),
            $this->getBuilderMock(),
            $this->getTransitionLogMock(),
            $this->getTimeoutMock(),
            new ReadOnlyArrayObject(),
            [],
            [static::COMMAND_NAME => $this->getCommandMock()],
            $this->getReservationMock(),
            new OmsConfig(),
        );
        $reflection = new ReflectionClass(OrderStateMachine::class);
        $reflectionProperty = $reflection->getProperty('commands');
        $reflectionProperty->setAccessible(true);
        $commands = $reflectionProperty->getValue($orderStateMachine);

        $this->assertInstanceOf(CommandCollectionInterface::class, $commands);
        $this->assertInstanceOf(CommandInterface::class, $commands->get(static::COMMAND_NAME));
    }

    /**
     * @return void
     */
    public function testInstantiationWithCommandCollection(): void
    {
        $commandCollection = new CommandCollection();
        $commandCollection->add($this->getCommandMock(), static::COMMAND_NAME);

        $orderStateMachine = new OrderStateMachine(
            $this->getQueryContainerMock(),
            $this->getBuilderMock(),
            $this->getTransitionLogMock(),
            $this->getTimeoutMock(),
            new ReadOnlyArrayObject(),
            [],
            $commandCollection,
            $this->getReservationMock(),
            new OmsConfig(),
        );
        $reflection = new ReflectionClass(OrderStateMachine::class);
        $reflectionProperty = $reflection->getProperty('commands');
        $reflectionProperty->setAccessible(true);
        $commands = $reflectionProperty->getValue($orderStateMachine);

        $this->assertInstanceOf(CommandCollectionInterface::class, $commands);
        $this->assertInstanceOf(CommandInterface::class, $commands->get(static::COMMAND_NAME));
    }

    /**
     * @return array<array>
     */
    public function conditionDataProvider(): array
    {
        return [
            'no store name, no limit' => [3, null, null],
            'no store name, limit' => [1, null, 1], // Will take only first created order
            'US store, no limit' => [2, 'US', null],
            'DE store, no limit' => [1, 'DE', null],
            'US store, no limit, single processor identifier' => [2, 'US', null, [2]],
            'US store, no limit, multiple processor identifiers' => [2, 'US', null, [1, 2]],
        ];
    }

    /**
     * This method will always create 2 orders:
     * - One DE order with one order item in a defined state.
     * - One US order with two order items in a defined state.
     *
     * @dataProvider conditionDataProvider()
     *
     * @param int $expectedAffectedOrderItemsCount
     * @param string|null $storeName
     * @param int|null $limit
     * @param array<int> $omsProcessorIdentifiers
     *
     * @return void
     */
    public function testCheckConditionsWithCriteria(
        int $expectedAffectedOrderItemsCount,
        ?string $storeName = null,
        ?int $limit = null,
        array $omsProcessorIdentifiers = []
    ): void {
        // Arrange
        $stateName = 'condition-test';
        $processName = 'DummyPayment01';

        $methods = [
            'createStateToTransitionMap',
            'updateStateByTransition',
            'saveOrderItems',
            'filterItemsWithOnEnterEvent',
            'triggerOnEnterEvents',
            'runCommand',
        ];

        $orderStateMachineMock = $this->getOrderStatemachineMockForConditionsWithCriteriaTest($methods);
        $orderStateMachineMock->method('createStateToTransitionMap')->willReturn([$stateName => $stateName]);
        $orderStateMachineMock->method('updateStateByTransition')->willReturn([]);
        $orderStateMachineMock->method('filterItemsWithOnEnterEvent')->willReturn([]);

        $this->tester->createOrderWithOrderItemsInStateAndProcessForStore('DE', $stateName, $processName, 1, 1);
        $this->tester->createOrderWithOrderItemsInStateAndProcessForStore('US', $stateName, $processName, 2, 2);

        $omsCheckConditionQueryCriteriaTransfer = new OmsCheckConditionsQueryCriteriaTransfer();
        $omsCheckConditionQueryCriteriaTransfer
            ->setStoreName($storeName)
            ->setOmsProcessorIdentifiers($omsProcessorIdentifiers)
            ->setLimit($limit);

        // Act
        $affectedOrderItems = $orderStateMachineMock->checkConditions([], $omsCheckConditionQueryCriteriaTransfer);

        // Assert
        $this->assertSame(
            $expectedAffectedOrderItemsCount,
            $affectedOrderItems,
            sprintf('Expected "%s" sales order items but "%s" are processed.', $expectedAffectedOrderItemsCount, $affectedOrderItems),
        );
    }

    /**
     * @param array $methods
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachine
     */
    protected function getOrderStatemachineMockForConditionsWithCriteriaTest(array $methods): OrderStateMachine
    {
        $conditionCollection = new ConditionCollection();
        $conditionCollection->add($this->getConditionMock(), static::CONDITION_NAME);

        /** @var \Spryker\Zed\Oms\OmsConfig $omsConfigMock */
        $omsConfigMock = $this->tester->getModuleConfig();
        $omsBusinessFactory = new OmsBusinessFactory();
        $omsBusinessFactory->setConfig($omsConfigMock);

        $orderStateMachineMockBuilder = $this
            ->getMockBuilder(OrderStateMachine::class)
            ->setConstructorArgs([
                new OmsQueryContainer(),
                $omsBusinessFactory->createOrderStateMachineBuilder(),
                $omsBusinessFactory->createUtilTransitionLog([]),
                $omsBusinessFactory->createOrderStateMachineTimeout(),
                $omsBusinessFactory->createUtilReadOnlyArrayObject($omsConfigMock->getActiveProcesses()),
                $conditionCollection,
                [],
                $omsBusinessFactory->createUtilReservation(),
                $omsConfigMock,
            ])
            ->onlyMethods($methods);

        return $orderStateMachineMockBuilder->getMock();
    }

    /**
     * @dataProvider errorMessagesDataProvider()
     *
     * @param string $messageText
     * @param \Exception $exception
     *
     * @return void
     */
    public function testTriggerEventReturnsCorrectErrorMessageInCaseOfCommandException(
        string $messageText,
        Exception $exception
    ) {
        // Arrange
        $commandMock = $this->getMockBuilder(CommandByOrderInterface::class)->getMock();
        $commandMock->method('run')->willThrowException($exception);

        $spySalesOrderItemMock = $this->getMockBuilder(SpySalesOrderItem::class)->getMock();
        $spyOmsOrderItemStateMock = $this->getMockBuilder(SpyOmsOrderItemState::class)->getMock();
        $spyOmsOrderProcessMock = $this->getMockBuilder(SpyOmsOrderProcess::class)->getMock();
        $spyOmsOrderProcessMock->method('getName')->willReturn('test');
        $spySalesOrderItemMock->method('getState')->willReturn($spyOmsOrderItemStateMock);
        $spySalesOrderItemMock->method('getProcess')->willReturn($spyOmsOrderProcessMock);
        $spySalesOrderItemMock->method('getOrder')->willReturn(
            $this->getMockBuilder(ChildSpySalesOrder::class)->getMock(),
        );

        $orderStateMachineMock = $this->getOrderStatemachineMockForConditionsWithCriteriaTest([
            'getCommand',
            'groupByOrderAndState',
            'logSourceState',
            'getProcesses',
            'initTransitionLog',
        ]);
        $orderStateMachineMock->method('getCommand')->willReturn($commandMock);
        $orderStateMachineMock->method('groupByOrderAndState')->willReturn(['key' => [$spySalesOrderItemMock]]);
        $eventMock = $this->getMockBuilder(EventInterface::class)->getMock();
        $eventMock->method('hasCommand')->willReturn(true);
        $stateMock = $this->getMockBuilder(StateInterface::class)->getMock();
        $stateMock->method('getEvent')->willReturn($eventMock);
        $processMock = $this->getMockBuilder(ProcessInterface::class)->getMock();
        $processMock->method('getStateFromAllProcesses')->willReturn($stateMock);
        $orderStateMachineMock->method('getProcesses')->willReturn(['test' => $processMock]);
        $transitionLogMock = $this->getMockBuilder(TransitionLogInterface::class)->getMock();
        $orderStateMachineMock->method('initTransitionLog')->willReturn($transitionLogMock);

        // Act
        $triggerEventReturnData = $orderStateMachineMock->triggerEvent('test', [], []);

        // Assert
        $this->assertInstanceOf(
            OmsEventTriggerResponseTransfer::class,
            $triggerEventReturnData[OmsConfig::OMS_EVENT_TRIGGER_RESPONSE],
        );
        $this->assertFalse($triggerEventReturnData[OmsConfig::OMS_EVENT_TRIGGER_RESPONSE]->getIsSuccessful());
        $this->assertCount(1, $triggerEventReturnData[OmsConfig::OMS_EVENT_TRIGGER_RESPONSE]->getMessages());
        $this->assertEquals($messageText, $triggerEventReturnData[OmsConfig::OMS_EVENT_TRIGGER_RESPONSE]->getMessages()[0]->getValue());
    }

    /**
     * @return array<array>
     */
    public function errorMessagesDataProvider(): array
    {
        return [
            ['test exception', new Exception('test exception')],
            ['Currently not executable.', new Exception()],
        ];
    }

    /**
     * @return array
     */
    public function transitionOrderItemsDataProvider(): array
    {
        return [
            'fallback query' => [null, null],
            'store filter query' => ['DE', null],
            'limit filter query' => [null, 1],
            'store and limit filter query' => ['DE', 1],
        ];
    }

    /**
     * @dataProvider transitionOrderItemsDataProvider()
     *
     * @param string|null $storeName
     * @param int|null $limit
     *
     * @return void
     */
    public function testCheckConditionsWillTransitionItemsToNewState(?string $storeName = null, ?int $limit = null)
    {
        // Arrange
        $orderItemStateName = 'payment pending';
        $salesOrderEntity = $this->tester->createOrderWithExpiredEventTimeoutOrderItemsForStore('DE', 'pay', $orderItemStateName, 1);
        $salesOrderItemCollection = $salesOrderEntity->getItems();
        $itemsWithExpectedSourceState = [];
        foreach ($salesOrderItemCollection as $salesOrderItem) {
            if ($salesOrderItem->getState()->getName() === $orderItemStateName) {
                $itemsWithExpectedSourceState[] = $salesOrderItem;
            }
        }

        $omsCheckConditionsQueryCriteriaTransfer = new OmsCheckConditionsQueryCriteriaTransfer();
        $omsCheckConditionsQueryCriteriaTransfer
            ->setStoreName($storeName)
            ->setLimit($limit);

        $orderStateMachineMock = $this->createOrderStatemachineMockForCheckConditionsWillTransitionOrderItem($orderItemStateName, 'paid');

        // Act
        $affectedOrderItems = $orderStateMachineMock->checkConditions([], $omsCheckConditionsQueryCriteriaTransfer);

        // Assert
        $expectedAffectedOrderItemsCount = 1;
        $this->assertSame(
            $expectedAffectedOrderItemsCount,
            $affectedOrderItems,
            sprintf('Expected "%s" sales order items but "%s" are processed.', $expectedAffectedOrderItemsCount, $affectedOrderItems),
        );

        $this->assertOrderItemsTransitionedIntoNewState($itemsWithExpectedSourceState, 'paid');
    }

    /**
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $salesOrderItems
     * @param string $expectedOrderItemStateAfterTransition
     *
     * @return void
     */
    protected function assertOrderItemsTransitionedIntoNewState(array $salesOrderItems, string $expectedOrderItemStateAfterTransition): void
    {
        foreach ($salesOrderItems as $salesOrderItem) {
            $salesOrderItem->reload();
            $orderItemStateName = $salesOrderItem->getState()->getName();

            $this->assertSame(
                $expectedOrderItemStateAfterTransition,
                $orderItemStateName,
                sprintf(
                    'Expected order item "%s" to be in state "%s" but state is "%s"',
                    $salesOrderItem->getIdSalesOrderItem(),
                    $expectedOrderItemStateAfterTransition,
                    $orderItemStateName,
                ),
            );
        }
    }

    /**
     * @param string $sourceStateName
     * @param string $targetStateName
     *
     * @return \Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachine
     */
    protected function createOrderStatemachineMockForCheckConditionsWillTransitionOrderItem(string $sourceStateName, string $targetStateName): OrderStateMachine
    {
        $this->tester->mockConfigMethod('getActiveProcesses', ['DummyPayment01']);
        $orderStateMachineMock = $this->getOrderStatemachineMockForConditionsWithCriteriaTest(['runCommand', 'createStateToTransitionMap', 'checkCondition']);

        $stateToTransitionMap = [$sourceStateName => [$targetStateName]];
        $orderStateMachineMock->method('createStateToTransitionMap')->willReturn($stateToTransitionMap);

        $state = new State();
        $state->setName($targetStateName);

        $orderStateMachineMock->method('checkCondition')->willReturn($state);

        return $orderStateMachineMock;
    }

    /**
     * @param array $methods
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachine
     */
    protected function createOrderStateMachineMock(array $methods): OrderStateMachine
    {
        /** @var \Spryker\Zed\Oms\OmsConfig $omsConfigMock */
        $omsConfigMock = $this->tester->getModuleConfig();
        $omsBusinessFactory = new OmsBusinessFactory();
        $omsBusinessFactory->setConfig($omsConfigMock);

        return $this->getMockBuilder(OrderStateMachine::class)
            ->setConstructorArgs([
                new OmsQueryContainer(),
                $omsBusinessFactory->createOrderStateMachineBuilder(),
                $omsBusinessFactory->createUtilTransitionLog([]),
                $omsBusinessFactory->createOrderStateMachineTimeout(),
                $omsBusinessFactory->createUtilReadOnlyArrayObject($omsConfigMock->getActiveProcesses()),
                $omsBusinessFactory->getProvidedDependency(OmsDependencyProvider::CONDITION_PLUGINS),
                $omsBusinessFactory->getProvidedDependency(OmsDependencyProvider::COMMAND_PLUGINS),
                $omsBusinessFactory->createUtilReservation(),
                $omsConfigMock,
            ])
            ->onlyMethods($methods)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface
     */
    private function getQueryContainerMock(): OmsQueryContainerInterface
    {
        return $this->getMockBuilder(OmsQueryContainerInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface
     */
    private function getBuilderMock(): BuilderInterface
    {
        return $this->getMockBuilder(BuilderInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Oms\Business\Util\TransitionLogInterface
     */
    private function getTransitionLogMock(): TransitionLogInterface
    {
        return $this->getMockBuilder(TransitionLogInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Oms\Business\OrderStateMachine\TimeoutInterface
     */
    private function getTimeoutMock(): TimeoutInterface
    {
        return $this->getMockBuilder(TimeoutInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface
     */
    private function getConditionMock(): ConditionInterface
    {
        return $this->getMockBuilder(ConditionInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Oms\Dependency\Plugin\Command\CommandInterface
     */
    private function getCommandMock(): CommandInterface
    {
        return $this->getMockBuilder(CommandInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Oms\Business\Util\ReservationInterface
     */
    private function getReservationMock(): ReservationInterface
    {
        return $this->getMockBuilder(ReservationInterface::class)
            ->getMock();
    }
}
