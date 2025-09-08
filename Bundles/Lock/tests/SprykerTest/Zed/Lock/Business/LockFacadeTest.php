<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Lock\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LockTransfer;
use Spryker\Zed\Lock\Business\LockBusinessFactory;
use Spryker\Zed\Lock\Business\LockFacade;
use Spryker\Zed\Lock\Business\LockMechanism\LockMechanismInterface;
use SprykerTest\Zed\Lock\LockBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Lock
 * @group Business
 * @group Facade
 * @group LockFacadeTest
 * Add your own group annotations below this line
 */
class LockFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Lock\LockBusinessTester
     */
    protected LockBusinessTester $tester;

    /**
     * @return void
     */
    public function testAcquireLockReturnsTrueWhenLockIsAcquired(): void
    {
        // Arrange
        $lockTransfer = (new LockTransfer())
            ->setKey('1')
            ->setEntityName('test');
        $lockMechanismMock = $this->createMock(LockMechanismInterface::class);
        $lockMechanismMock->method('acquireLock')->willReturn($lockTransfer->setResult(true));

        $factoryMock = $this->createMock(LockBusinessFactory::class);
        $factoryMock->method('createLockMechanism')->willReturn($lockMechanismMock);

        $facade = new LockFacade();
        $facade->setFactory($factoryMock);

        // Act
        $result = $facade->acquireLock($lockTransfer);

        // Assert
        $this->assertTrue($result->getResult());
    }

    /**
     * @return void
     */
    public function testReleaseLockCallsReleaseLockOnMechanism(): void
    {
        // Arrange
        $lockTransfer = (new LockTransfer())
            ->setKey('1')
            ->setEntityName('test');
        $lockMechanismMock = $this->createMock(LockMechanismInterface::class);
        $lockMechanismMock->expects($this->once())
            ->method('releaseLock')
            ->with($lockTransfer);

        $factoryMock = $this->createMock(LockBusinessFactory::class);
        $factoryMock->method('createLockMechanism')->willReturn($lockMechanismMock);

        $facade = new LockFacade();
        $facade->setFactory($factoryMock);

        // Act
        $facade->releaseLock($lockTransfer);

        // Assert
        // (Expectation is set above)
    }
}
