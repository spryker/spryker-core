<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Communication\Plugin\Oms\Command;

use Codeception\Test\Unit;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandCollection;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandCollectionInterface;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandInterface;
use Spryker\Zed\Oms\Exception\CommandNotFoundException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Communication
 * @group Plugin
 * @group Oms
 * @group Command
 * @group CommandCollectionTest
 * Add your own group annotations below this line
 */
class CommandCollectionTest extends Unit
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'commandName';

    /**
     * @return void
     */
    public function testAddShouldReturnInstance(): void
    {
        $commandCollection = new CommandCollection();
        $result = $commandCollection->add($this->getCommandMock(), static::COMMAND_NAME);

        $this->assertInstanceOf(CommandCollectionInterface::class, $result);
    }

    /**
     * @return void
     */
    public function testHasShouldReturnFalse(): void
    {
        $commandCollection = new CommandCollection();

        $this->assertFalse($commandCollection->has(static::COMMAND_NAME));
    }

    /**
     * @return void
     */
    public function testHasShouldReturnTrue(): void
    {
        $commandCollection = new CommandCollection();
        $command = $this->getCommandMock();
        $commandCollection->add($command, static::COMMAND_NAME);

        $this->assertTrue($commandCollection->has(static::COMMAND_NAME));
    }

    /**
     * @return void
     */
    public function testGetShouldReturnCommand(): void
    {
        $commandCollection = new CommandCollection();
        $command = $this->getCommandMock();
        $commandCollection->add($command, static::COMMAND_NAME);

        $this->assertSame($command, $commandCollection->get(static::COMMAND_NAME));
    }

    /**
     * @return void
     */
    public function testGetShouldThrowException(): void
    {
        $commandCollection = new CommandCollection();

        $this->expectException(CommandNotFoundException::class);

        $commandCollection->get(static::COMMAND_NAME);
    }

    /**
     * @return void
     */
    public function testArrayAccess(): void
    {
        $commandCollection = new CommandCollection();
        $this->assertFalse(isset($commandCollection[static::COMMAND_NAME]));

        $condition = $this->getCommandMock();
        $commandCollection[static::COMMAND_NAME] = $condition;

        $this->assertTrue(isset($commandCollection[static::COMMAND_NAME]));
        $this->assertSame($condition, $commandCollection[static::COMMAND_NAME]);

        unset($commandCollection[static::COMMAND_NAME]);
        $this->assertFalse(isset($commandCollection[static::COMMAND_NAME]));
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Oms\Dependency\Plugin\Command\CommandInterface
     */
    private function getCommandMock(): CommandInterface
    {
        return $this->getMockBuilder(CommandInterface::class)->getMock();
    }
}
