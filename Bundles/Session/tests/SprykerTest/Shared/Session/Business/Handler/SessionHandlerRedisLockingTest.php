<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Session\Business\Handler;

use Codeception\Test\Unit;
use Predis\Client;
use Spryker\Shared\Session\Business\Handler\KeyGenerator\Redis\RedisSessionKeyGenerator;
use Spryker\Shared\Session\Business\Handler\Lock\Redis\RedisSpinLockLocker;
use Spryker\Shared\Session\Business\Handler\SessionHandlerRedisLocking;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Session
 * @group Business
 * @group Handler
 * @group SessionHandlerRedisLockingTest
 * Add your own group annotations below this line
 */
class SessionHandlerRedisLockingTest extends Unit
{
    /**
     * @return void
     */
    public function testReadReturnsEmptyStringOnMissingSessionKey()
    {
        $redisClientMock = $this->getRedisClientMock(null);
        $lockerMock = $this->getRedisSpinLockLockerMock();
        $sessionHandler = $this->getSessionHandlerRedisLocking($redisClientMock, $lockerMock);

        $sessionData = $sessionHandler->read('session_key');

        $this->assertSame('', $sessionData);
    }

    /**
     * @return void
     */
    public function testReadDecodesLegacyJsonSession()
    {
        $redisClientMock = $this->getRedisClientMock(json_encode('foo bar baz'));
        $lockerMock = $this->getRedisSpinLockLockerMock();
        $sessionHandler = $this->getSessionHandlerRedisLocking($redisClientMock, $lockerMock);

        $sessionData = $sessionHandler->read('session_key');

        $this->assertSame('foo bar baz', $sessionData);
    }

    /**
     * @param string|null $returnValue
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Predis\Client
     */
    private function getRedisClientMock($returnValue)
    {
        $redisClientMock = $this
            ->getMockBuilder(Client::class)
            ->getMock();

        $redisClientMock
            ->expects($this->once())
            ->method('__call')
            ->with($this->equalTo('get'), ['session:session_key'])
            ->will($this->returnValue($returnValue));

        return $redisClientMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Session\Business\Handler\Lock\Redis\RedisSpinLockLocker
     */
    private function getRedisSpinLockLockerMock()
    {
        $lockerMock = $this
            ->getMockBuilder(RedisSpinLockLocker::class)
            ->disableOriginalConstructor()
            ->getMock();

        $lockerMock
            ->expects($this->once())
            ->method('lock')
            ->will($this->returnValue(true));

        return $lockerMock;
    }

    /**
     * @param \Predis\Client $redisClientMock
     * @param \Spryker\Shared\Session\Business\Handler\Lock\Redis\RedisSpinLockLocker $lockerMock
     *
     * @return \Spryker\Shared\Session\Business\Handler\SessionHandlerRedisLocking
     */
    private function getSessionHandlerRedisLocking(Client $redisClientMock, RedisSpinLockLocker $lockerMock)
    {
        $sessionHandler = new SessionHandlerRedisLocking(
            $redisClientMock,
            $lockerMock,
            new RedisSessionKeyGenerator(),
            60
        );

        return $sessionHandler;
    }
}
