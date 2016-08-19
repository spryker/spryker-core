<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Oms\Business\OrderStateMachine;

use Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface;
use Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachine;
use Spryker\Zed\Oms\Business\OrderStateMachine\TimeoutInterface;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Business\Util\TransitionLogInterface;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandCollection;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandCollectionInterface;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandInterface;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionCollection;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionCollectionInterface;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface;
use Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Oms
 * @group Business
 * @group OrderStateMachine
 * @group OrderStateMachineTest
 */
class OrderStateMachineTest extends \PHPUnit_Framework_TestCase
{

    const CONDITION_NAME = 'conditionName';
    const COMMAND_NAME = 'commandName';

    /**
     * @return void
     */
    public function testInstantiationConditionsArrayShouldConvertedToCollection()
    {
        $orderStateMachine = new OrderStateMachine(
            $this->getQueryContainerMock(),
            $this->getBuilderMock(),
            $this->getTransitionLogMock(),
            $this->getTimeoutMock(),
            new ReadOnlyArrayObject(),
            [self::CONDITION_NAME => $this->getConditionMock()],
            []
        );
        $reflection = new \ReflectionClass(OrderStateMachine::class);
        $reflectionProperty = $reflection->getProperty('conditions');
        $reflectionProperty->setAccessible(true);
        $conditions = $reflectionProperty->getValue($orderStateMachine);

        $this->assertInstanceOf(ConditionCollectionInterface::class, $conditions);
        $this->assertInstanceOf(ConditionInterface::class, $conditions->get(self::CONDITION_NAME));
    }

    /**
     * @return void
     */
    public function testInstantiationWithConditionCollection()
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
            []
        );
        $reflection = new \ReflectionClass(OrderStateMachine::class);
        $reflectionProperty = $reflection->getProperty('conditions');
        $reflectionProperty->setAccessible(true);
        $conditions = $reflectionProperty->getValue($orderStateMachine);

        $this->assertInstanceOf(ConditionCollectionInterface::class, $conditions);
        $this->assertInstanceOf(ConditionInterface::class, $conditions->get(self::CONDITION_NAME));
    }

    /**
     * @return void
     */
    public function testInstantiationCommandsArrayShouldConvertedToCollection()
    {
        $orderStateMachine = new OrderStateMachine(
            $this->getQueryContainerMock(),
            $this->getBuilderMock(),
            $this->getTransitionLogMock(),
            $this->getTimeoutMock(),
            new ReadOnlyArrayObject(),
            [],
            [self::COMMAND_NAME => $this->getCommandMock()]
        );
        $reflection = new \ReflectionClass(OrderStateMachine::class);
        $reflectionProperty = $reflection->getProperty('commands');
        $reflectionProperty->setAccessible(true);
        $commands = $reflectionProperty->getValue($orderStateMachine);

        $this->assertInstanceOf(CommandCollectionInterface::class, $commands);
        $this->assertInstanceOf(CommandInterface::class, $commands->get(self::COMMAND_NAME));
    }

    /**
     * @return void
     */
    public function testInstantiationWithCommandCollection()
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
            $commandCollection
        );
        $reflection = new \ReflectionClass(OrderStateMachine::class);
        $reflectionProperty = $reflection->getProperty('commands');
        $reflectionProperty->setAccessible(true);
        $commands = $reflectionProperty->getValue($orderStateMachine);

        $this->assertInstanceOf(CommandCollectionInterface::class, $commands);
        $this->assertInstanceOf(CommandInterface::class, $commands->get(self::COMMAND_NAME));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface
     */
    private function getQueryContainerMock()
    {
        return $this->getMock(OmsQueryContainerInterface::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface
     */
    private function getBuilderMock()
    {
        return $this->getMock(BuilderInterface::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Oms\Business\Util\TransitionLogInterface
     */
    private function getTransitionLogMock()
    {
        return $this->getMock(TransitionLogInterface::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Oms\Business\OrderStateMachine\TimeoutInterface
     */
    private function getTimeoutMock()
    {
        return $this->getMock(TimeoutInterface::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface
     */
    private function getConditionMock()
    {
        return $this->getMock(ConditionInterface::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandInterface
     */
    private function getCommandMock()
    {
        return $this->getMock(CommandInterface::class);
    }

}
