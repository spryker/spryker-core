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
        $stateName = 'condition-test';

        $orderStateMachineMock = $this->getOrderStatemachineMockForConditionsWithCriteriaTest([
            $stateName => $stateName,
        ]);

        $this->tester->createOrderWithOrderItemsInStateForStore('DE', $stateName, 1);
        $this->tester->createOrderWithOrderItemsInStateForStore('US', $stateName, 2);

        $omsCheckConditionQueryCriteriaTransfer = new OmsCheckConditionsQueryCriteriaTransfer();
        $omsCheckConditionQueryCriteriaTransfer
            ->setStoreName($storeName)
            ->setLimit($limit);

        $this->assertSame($expectedAffectedOrderItemsCount, $orderStateMachineMock->checkConditions([], $omsCheckConditionQueryCriteriaTransfer));
    }

    /**
     * @param array $statesForTransition
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachine
     */
    protected function getOrderStatemachineMockForConditionsWithCriteriaTest(array $statesForTransition): OrderStateMachine
    {
        $conditionCollection = new ConditionCollection();
        $conditionCollection->add($this->getConditionMock(), self::CONDITION_NAME);

        $omsConfig = new OmsConfig();
        $omsBusinessFactory = new OmsBusinessFactory();
        $activeProcesses = new ReadOnlyArrayObject(['DummyPayment01']);

        $orderStateMachineMockBuilder = $this
            ->getMockBuilder(OrderStateMachine::class)
            ->setConstructorArgs([
                new OmsQueryContainer(),
                $omsBusinessFactory->createOrderStateMachineBuilder(),
                $this->getTransitionLogMock(),
                $this->getTimeoutMock(),
                $activeProcesses,
                $conditionCollection,
                [],
                $this->getReservationMock(),
                $omsConfig,
            ])
            ->onlyMethods([
                'createStateToTransitionMap',
                'updateStateByTransition',
                'saveOrderItems',
                'filterItemsWithOnEnterEvent',
                'triggerOnEnterEvents',
            ]);

        $orderStateMachineMock = $orderStateMachineMockBuilder->getMock();
        $orderStateMachineMock->method('createStateToTransitionMap')->willReturn($statesForTransition);
        $orderStateMachineMock->method('updateStateByTransition')->willReturn([]);
        $orderStateMachineMock->method('filterItemsWithOnEnterEvent')->willReturn([]);

        return $orderStateMachineMock;
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
