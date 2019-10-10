<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\SessionRedis\Handler\Lock;

use Codeception\Test\Unit;
use Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilder;
use Spryker\Shared\SessionRedis\Handler\Lock\SessionSpinLockLocker;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group SessionRedis
 * @group Handler
 * @group Lock
 * @group SpinLockLockerTest
 * Add your own group annotations below this line
 */
class SpinLockLockerTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface
     */
    protected $redisClientMock;

    /**
     * @var \Spryker\Shared\SessionRedis\Handler\Lock\SessionLockerInterface
     */
    protected $spinLockLocker;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->setupRedisClientMock();
        $this->setupRedisSpinLockLocker();
    }

    /**
     * @return void
     */
    public function testLockBlocksUntilLockIsAcquired(): void
    {
        $this->redisClientMock->expects($this->exactly(3))
            ->method('set')
            ->with($this->anything())
            ->will($this->onConsecutiveCalls(0, 0, 1));

        $this->spinLockLocker->lock('session_id');
    }

    /**
     * @return void
     */
    public function testUnlockUsesGeneratedKeyFromStoredSessionId(): void
    {
        $sessionId = 'test_session_id';
        $expectedGeneratedKey = "session:{$sessionId}:lock";

        $this->redisClientMock->expects($this->once())
            ->method('eval')
            ->with(
                $this->anything(),
                1,
                $expectedGeneratedKey
            );
        $this->redisClientMock->expects($this->once())
            ->method('set')
            ->willReturn(true);

        $this->spinLockLocker->lock($sessionId);
        $this->spinLockLocker->unlockCurrent();
    }

    /**
     * @return void
     */
    public function testDoesNotPerformUnlockWhenNotLocked(): void
    {
        $this->redisClientMock
            ->expects($this->never())
            ->method('eval');

        $this->spinLockLocker->unlockCurrent();
    }

    /**
     * @return void
     */
    protected function setupRedisClientMock(): void
    {
        $this->redisClientMock = $this
            ->getMockBuilder(SessionRedisWrapperInterface::class)
            ->getMock();
    }

    /**
     * @return void
     */
    protected function setupRedisSpinLockLocker(): void
    {
        $this->spinLockLocker = new SessionSpinLockLocker($this->redisClientMock, new SessionKeyBuilder());
    }
}
