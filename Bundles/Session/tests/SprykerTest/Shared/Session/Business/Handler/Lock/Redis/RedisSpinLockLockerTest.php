<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Session\Business\Handler\Lock\Redis;

use Codeception\Test\Unit;
use Predis\Client;
use Spryker\Shared\Session\Business\Handler\KeyGenerator\Redis\RedisLockKeyGenerator;
use Spryker\Shared\Session\Business\Handler\KeyGenerator\Redis\RedisSessionKeyGenerator;
use Spryker\Shared\Session\Business\Handler\Lock\Redis\RedisSpinLockLocker;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Session
 * @group Business
 * @group Handler
 * @group Lock
 * @group Redis
 * @group RedisSpinLockLockerTest
 * Add your own group annotations below this line
 */
class RedisSpinLockLockerTest extends Unit
{
    /**
     * @return void
     */
    public function testLockBlocksUntilLockIsAcquired()
    {
        $redisClientMock = $this->getRedisClientMock();
        $redisClientMock
            ->expects($this->exactly(3))
            ->method('__call')
            ->with($this->equalTo('set'), $this->anything())
            ->will($this->onConsecutiveCalls(0, 0, 1));

        $locker = new RedisSpinLockLocker($redisClientMock, new RedisLockKeyGenerator(new RedisSessionKeyGenerator()));
        $locker->lock('session_id');
    }

    /**
     * @return void
     */
    public function testUnlockUsesGeneratedKeyFromStoredSessionId()
    {
        $sessionId = 'test_session_id';
        $expectedGeneratedKey = "session:{$sessionId}:lock";
        $redisClientMock = $this->getRedisClientMock();
        $redisClientMock
            ->expects($this->exactly(2))
            ->method('__call')
            ->withConsecutive(
                [$this->equalTo('set'), $this->anything()],
                [$this->equalTo('eval'), $this->contains($expectedGeneratedKey)]
            )
            ->will($this->onConsecutiveCalls(1, 1));

        $locker = new RedisSpinLockLocker($redisClientMock, new RedisLockKeyGenerator(new RedisSessionKeyGenerator()));
        $locker->lock($sessionId);
        $locker->unlockCurrent();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Predis\Client
     */
    private function getRedisClientMock()
    {
        return $this
            ->getMockBuilder(Client::class)
            ->getMock();
    }
}
