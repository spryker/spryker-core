<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business\Util;

use Codeception\Test\Unit;
use ReflectionClass;
use Spryker\Shared\Graph\GraphInterface;
use Spryker\Zed\Oms\Business\Util\Drawer;
use Spryker\Zed\Oms\Business\Util\TimeoutProcessorCollection;
use Spryker\Zed\Oms\Business\Util\TimeoutProcessorCollectionInterface;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandCollection;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandCollectionInterface;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionCollection;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandInterface;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionCollectionInterface;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;
use Spryker\Zed\Oms\Dependency\Service\OmsToUtilTextInterface;
use Spryker\Zed\OmsExtension\Dependency\Plugin\TimeoutProcessorPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Business
 * @group Util
 * @group DrawerTest
 * Add your own group annotations below this line
 */
class DrawerTest extends Unit
{
    public const CONDITION_NAME = 'conditionName';
    public const COMMAND_NAME = 'commandName';

    protected const TIMEOUT_PROCESSOR_NAME = 'Test/TimeoutProcessorName';
    protected const TIMEOUT_PROCESSOR_LABEL = 'TimeoutProcessor test label';

    /**
     * @return void
     */
    public function testInstantiationConditionsArrayShouldConvertedToCollection(): void
    {
        $drawer = new Drawer(
            [],
            [self::CONDITION_NAME => $this->getConditionMock()],
            $this->getGraphMock(),
            $this->getOmsToUtilTextServiceMock(),
            $this->getTimeoutProcessorCollectionMock()
        );
        $reflection = new ReflectionClass(Drawer::class);
        $reflectionProperty = $reflection->getProperty('conditions');
        $reflectionProperty->setAccessible(true);
        $conditions = $reflectionProperty->getValue($drawer);

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

        $drawer = new Drawer(
            [],
            $conditionCollection,
            $this->getGraphMock(),
            $this->getOmsToUtilTextServiceMock(),
            $this->getTimeoutProcessorCollectionMock()
        );
        $reflection = new ReflectionClass(Drawer::class);
        $reflectionProperty = $reflection->getProperty('conditions');
        $reflectionProperty->setAccessible(true);
        $conditions = $reflectionProperty->getValue($drawer);

        $this->assertInstanceOf(ConditionCollectionInterface::class, $conditions);
        $this->assertInstanceOf(ConditionInterface::class, $conditions->get(self::CONDITION_NAME));
    }

    /**
     * @return void
     */
    public function testInstantiationCommandsArrayShouldConvertedToCollection(): void
    {
        $drawer = new Drawer(
            [self::COMMAND_NAME => $this->getCommandMock()],
            [],
            $this->getGraphMock(),
            $this->getOmsToUtilTextServiceMock(),
            $this->getTimeoutProcessorCollectionMock()
        );
        $reflection = new ReflectionClass(Drawer::class);
        $reflectionProperty = $reflection->getProperty('commands');
        $reflectionProperty->setAccessible(true);
        $commands = $reflectionProperty->getValue($drawer);

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

        $drawer = new Drawer(
            $commandCollection,
            [],
            $this->getGraphMock(),
            $this->getOmsToUtilTextServiceMock(),
            $this->getTimeoutProcessorCollectionMock()
        );
        $reflection = new ReflectionClass(Drawer::class);
        $reflectionProperty = $reflection->getProperty('commands');
        $reflectionProperty->setAccessible(true);
        $commands = $reflectionProperty->getValue($drawer);

        $this->assertInstanceOf(CommandCollectionInterface::class, $commands);
        $this->assertInstanceOf(CommandInterface::class, $commands->get(self::COMMAND_NAME));
    }

    /**
     * @return void
     */
    public function testInstantiationWithTimeoutProcessorCollection(): void
    {
        $timeoutProcessorPluginMock = $this->getTimeoutProcessorPluginMock();
        $timeoutProcessorCollection = new TimeoutProcessorCollection([$timeoutProcessorPluginMock]);

        $drawer = new Drawer(
            [],
            [],
            $this->getGraphMock(),
            $this->getOmsToUtilTextServiceMock(),
            $timeoutProcessorCollection
        );
        $reflection = new ReflectionClass(Drawer::class);
        $reflectionProperty = $reflection->getProperty('timeoutProcessorCollection');
        $reflectionProperty->setAccessible(true);
        $timeoutProcessorCollection = $reflectionProperty->getValue($drawer);

        $this->assertInstanceOf(TimeoutProcessorCollectionInterface::class, $timeoutProcessorCollection);
        $this->assertInstanceOf(TimeoutProcessorPluginInterface::class, $timeoutProcessorCollection->get(static::TIMEOUT_PROCESSOR_NAME));
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Graph\GraphInterface
     */
    private function getGraphMock(): GraphInterface
    {
        return $this->getMockBuilder(GraphInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Oms\Dependency\Service\OmsToUtilTextInterface
     */
    private function getOmsToUtilTextServiceMock(): OmsToUtilTextInterface
    {
        return $this->getMockBuilder(OmsToUtilTextInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Oms\Business\Util\TimeoutProcessorCollectionInterface
     */
    private function getTimeoutProcessorCollectionMock(): TimeoutProcessorCollectionInterface
    {
        return $this->getMockBuilder(TimeoutProcessorCollectionInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\OmsExtension\Dependency\Plugin\TimeoutProcessorPluginInterface
     */
    private function getTimeoutProcessorPluginMock(): TimeoutProcessorPluginInterface
    {
        $timeoutProcessorPluginMock = $this->getMockBuilder(TimeoutProcessorPluginInterface::class)->getMock();
        $timeoutProcessorPluginMock->method('getName')->willReturn(static::TIMEOUT_PROCESSOR_NAME);
        $timeoutProcessorPluginMock->method('getLabel')->willReturn(static::TIMEOUT_PROCESSOR_LABEL);

        return $timeoutProcessorPluginMock;
    }
}
