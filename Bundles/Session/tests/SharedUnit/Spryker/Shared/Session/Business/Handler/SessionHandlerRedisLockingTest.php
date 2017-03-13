<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SharedUnit\Spryker\Shared\Session\Business\Handler;

use Codeception\TestCase\Test;
use Predis\Client;
use Spryker\Shared\Session\Business\Handler\KeyGenerator\Redis\RedisSessionKeyGenerator;
use Spryker\Shared\Session\Business\Handler\Lock\Redis\RedisSpinLockLocker;
use Spryker\Shared\Session\Business\Handler\SessionHandlerRedisLocking;

/**
 * @group SharedUnit
 * @group Spryker
 * @group Shared
 * @group Session
 * @group Business
 * @group Handler
 * @group SessionHandlerRedisLockingTest
 */
class SessionHandlerRedisLockingTest extends Test
{

    /**
     * @return void
     */
    public function testReadReturnsEmptyStringOnMissingSessionKey()
    {
        $redisClientMock = $this->getRedisClientMock();
        $redisClientMock
            ->expects($this->once())
            ->method('__call')
            ->with($this->equalTo('get'), ['session:session_key'])
            ->will($this->returnValue(null));

        $lockerMock = $this->getRedisSpinLockLockerMock();
        $lockerMock
            ->expects($this->once())
            ->method('lock')
            ->will($this->returnValue(true));

        $sessionHandler = new SessionHandlerRedisLocking(
            $redisClientMock,
            $lockerMock,
            new RedisSessionKeyGenerator(),
            60
        );

        $sessionData = $sessionHandler->read('session_key');

        $this->assertSame('', $sessionData);
    }

    /**
     * @return void
     */
    public function testReadDecodesLegacyJsonSession()
    {
        $redisClientMock = $this->getRedisClientMock();
        $redisClientMock
            ->expects($this->once())
            ->method('__call')
            ->with($this->equalTo('get'), ['session:session_key'])
            ->will($this->returnValue(json_encode('foo bar baz')));

        $lockerMock = $this->getRedisSpinLockLockerMock();
        $lockerMock
            ->expects($this->once())
            ->method('lock')
            ->will($this->returnValue(true));

        $sessionHandler = new SessionHandlerRedisLocking(
            $redisClientMock,
            $lockerMock,
            new RedisSessionKeyGenerator(),
            60
        );

        $sessionData = $sessionHandler->read('session_key');

        $this->assertSame('foo bar baz', $sessionData);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Predis\Client
     */
    private function getRedisClientMock()
    {
        return $this
            ->getMockBuilder(Client::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Shared\Session\Business\Handler\Lock\Redis\RedisSpinLockLocker
     */
    private function getRedisSpinLockLockerMock()
    {
        return $this
            ->getMockBuilder(RedisSpinLockLocker::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

}
