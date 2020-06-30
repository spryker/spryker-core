<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business\OrderStateMachine;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OmsCheckConditionsQueryCriteriaTransfer;
use ReflectionClass;
use Spryker\Zed\Oms\Business\OmsBusinessFactory;
use Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface;
use Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachine;
use Spryker\Zed\Oms\Business\OrderStateMachine\TimeoutInterface;
use Spryker\Zed\Oms\Business\Process\State;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Business\Util\ReservationInterface;
use Spryker\Zed\Oms\Business\Util\TransitionLogInterface;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandCollection;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandCollectionInterface;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionCollection;
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
    public const CONDITION_NAME = 'conditionName';
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
            [self::CONDITION_NAME => $this->getConditionMock()],
            [],
            $this->getReservationMock(),
            new OmsConfig()
        );
        $reflection = new ReflectionClass(OrderStateMachine::class);
        $reflectionProperty = $reflection->getProperty('conditions');
        $reflectionProperty->setAccessible(true);
        $conditions = $reflectionProperty->getValue($orderStateMachine);

        $this->assertInstanceOf(ConditionCollectionInterface::class, $conditions);
        $this->assertInstanceOf(ConditionInterface::class, $conditions->get(self::CONDITION_NAME));
    }

    /**
     * @return void
     */
    public function testInstantiationWithConditionCollection(): void
    {
        $conditionCollection = new ConditionCollection();
        $conditionCollection->add($this->getConditionMock(), self::CONDITION_NAME);

        $orderStateMachine = new OrderStateMachine(
            $this->getQueryContainerMock(),
            $this->getBuilderMock(),
            $this->getTransitionLogMock(),
            $this->getTimeoutMock(),
            new ReadOnlyArrayObject(),
            $conditionCollection,
            [],
            $this->getReservationMock(),
            new OmsConfig()
        );
        $reflection = new ReflectionClass(OrderStateMachine::class);
        $reflectionProperty = $reflection->getProperty('conditions');
        $reflectionProperty->setAccessible(true);
        $conditions = $reflectionProperty->getValue($orderStateMachine);

        $this->assertInstanceOf(ConditionCollectionInterface::class, $conditions);
        $this->assertInstanceOf(ConditionInterface::class, $conditions->get(self::CONDITION_NAME));
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
            [self::COMMAND_NAME => $this->getCommandMock()],
            $this->getReservationMock(),
            new OmsConfig()
        );
        $reflection = new ReflectionClass(OrderStateMachine::class);
        $reflectionProperty = $reflection->getProperty('commands');
        $reflectionProperty->setAccessible(true);
        $commands = $reflectionProperty->getValue($orderStateMachine);

        $this->assertInstanceOf(CommandCollectionInterface::class, $commands);
        $this->assertInstanceOf(CommandInterface::class, $commands->get(self::COMMAND_NAME));
    }

    /**
     * @return void
     */
    public function testInstantiationWithCommandCollection(): void
    {
        $commandCollection = new CommandCollection();
        $commandCollection->add($this->getCommandMock(), self::COMMAND_NAME);

        $orderStateMachine = new OrderStateMachine(
            $this->getQueryContainerMock(),
            $this->getBuilderMock(),
            $this->getTransitionLogMock(),
            $this->getTimeoutMock(),
            new ReadOnlyArrayObject(),
            [],
            $commandCollection,
            $this->getReservationMock(),
            new OmsConfig()
        );
        $reflection = new ReflectionClass(OrderStateMachine::class);
        $reflectionProperty = $reflection->getProperty('commands');
        $reflectionProperty->setAccessible(true);
        $commands = $reflectionProperty->getValue($orderStateMachine);

        $this->assertInstanceOf(CommandCollectionInterface::class, $commands);
        $this->assertInstanceOf(CommandInterface::class, $commands->get(self::COMMAND_NAME));
    }

    /**
     * @return array[]
     */
    public function conditionDataProvider(): array
    {
        return [
            'no store name, no limit' => [3, null, null],
            'no store name, limit' => [1, null, 1], // Will take only first created order
            'US store, no limit' => [2, 'US', null],
            'DE store, no limit' => [1, 'DE', null],
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
     *
     * @return void
     */
    public function testCheckConditionsWithCriteria(int $expectedAffectedOrderItemsCount, ?string $storeName = null, ?int $limit = null): void
    {
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

        $this->tester->createOrderWithOrderItemsInStateAndProcessForStore('DE', $stateName, $processName, 1);
        $this->tester->createOrderWithOrderItemsInStateAndProcessForStore('US', $stateName, $processName, 2);

        $omsCheckConditionQueryCriteriaTransfer = new OmsCheckConditionsQueryCriteriaTransfer();
        $omsCheckConditionQueryCriteriaTransfer
            ->setStoreName($storeName)
            ->setLimit($limit);

        // Act
        $affectedOrderItems = $orderStateMachineMock->checkConditions([], $omsCheckConditionQueryCriteriaTransfer);

        // Assert
        $this->assertSame(
            $expectedAffectedOrderItemsCount,
            $affectedOrderItems,
            sprintf('Expected "%s" sales order items but "%s" are processed.', $expectedAffectedOrderItemsCount, $affectedOrderItems)
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
            sprintf('Expected "%s" sales order items but "%s" are processed.', $expectedAffectedOrderItemsCount, $affectedOrderItems)
        );

        $this->assertOrderItemsTransitionedIntoNewState($itemsWithExpectedSourceState, 'paid');
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItems
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
                    $orderItemStateName
                )
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
