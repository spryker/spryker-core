<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\SessionRedis\Handler;

use Codeception\Test\Unit;
use Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilder;
use Spryker\Shared\SessionRedis\Handler\Lock\SessionSpinLockLocker;
use Spryker\Shared\SessionRedis\Handler\SessionHandlerRedisLocking;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Shared
 * @group SessionRedis
 * @group Handler
 * @group SessionHandlerRedisLockingTest
 * Add your own group annotations below this line
 */
class SessionHandlerRedisLockingTest extends Unit
{
    protected const TIME_TO_LIVE = 60;

    /**
     * @var \Spryker\Shared\SessionRedis\Handler\SessionHandlerRedisLocking
     */
    protected $sessionHandler;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface
     */
    protected $redisClientMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\SessionRedis\Handler\Lock\SessionSpinLockLocker
     */
    protected $spinLockLockerMock;

    /**
     * @return void
     */
    public function testReadReturnsEmptyStringOnMissingSessionKey(): void
    {
        $this->redisClientMock
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo('session:session_key'))
            ->willReturn(null);

        $this->spinLockLockerMock
            ->expects($this->once())
            ->method('lock')
            ->willReturn(true);

        $sessionData = $this->sessionHandler->read('session_key');

        $this->assertSame('', $sessionData);
    }

    /**
     * @return void
     */
    public function testGcReturnsTrue(): void
    {
        $this->assertTrue($this->sessionHandler->gc(1));
    }

    /**
     * @expectedException \Spryker\Shared\SessionRedis\Handler\Exception\LockCouldNotBeAcquiredException
     *
     * @return void
     */
    public function testReadingSessionDataWillThrowExceptionWhenImpossibleToAcquireLock(): void
    {
        $this->spinLockLockerMock
            ->expects($this->once())
            ->method('lock')
            ->willReturn(false);

        $this->sessionHandler->read('session_key');
    }

    /**
     * @return void
     */
    public function testCanDestroySession(): void
    {
        $this->redisClientMock
            ->expects($this->once())
            ->method('del')
            ->with(['session:session_key'])
            ->willReturn(true);

        $this->spinLockLockerMock
            ->expects($this->once())
            ->method('unlockCurrent');

        $this->assertTrue($this->sessionHandler->destroy('session_key'));
    }

    /**
     * @return void
     */
    public function testDestructorUnlocksSessionDataAndDisconnectsFromRedis(): void
    {
        $this->redisClientMock
            ->expects($this->once())
            ->method('disconnect');

        $this->spinLockLockerMock
            ->expects($this->once())
            ->method('unlockCurrent');

        unset($this->sessionHandler);
    }

    /**
     * @return void
     */
    public function testClosingSessionUnlocksSessionData(): void
    {
        $this->spinLockLockerMock
            ->expects($this->once())
            ->method('unlockCurrent');

        $this->assertTrue($this->sessionHandler->close());
    }

    /**
     * @return void
     */
    public function testWritesSessionDataWithTtlSet(): void
    {
        $dummyData = 'foo bar baz';
        $this->redisClientMock
            ->expects($this->once())
            ->method('setex')
            ->with(
                $this->equalTo('session:session_key'),
                $this->equalTo(static::TIME_TO_LIVE),
                $this->equalTo($dummyData)
            )
            ->willReturn(true);

        $this->assertTrue($this->sessionHandler->write('session_key', $dummyData));
    }

    /**
     * @return void
     */
    public function testReadDecodesLegacyJsonSession(): void
    {
        $dummyData = 'foo bar baz';
        $this->redisClientMock
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo('session:session_key'))
            ->willReturn(json_encode($dummyData));

        $this->spinLockLockerMock
            ->expects($this->once())
            ->method('lock')
            ->willReturn(true);

        $sessionData = $this->sessionHandler->read('session_key');

        $this->assertSame($dummyData, $sessionData);
    }

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->setupRedisClientMock();
        $this->setupRedisSpinLockLockerMock();
        $this->setupSessionHandlerRedisLocking();
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
    protected function setupRedisSpinLockLockerMock(): void
    {
        $this->spinLockLockerMock = $this
            ->getMockBuilder(SessionSpinLockLocker::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return void
     */
    protected function setupSessionHandlerRedisLocking(): void
    {
        $this->sessionHandler = new SessionHandlerRedisLocking(
            $this->redisClientMock,
            $this->spinLockLockerMock,
            new SessionKeyBuilder(),
            static::TIME_TO_LIVE
        );
    }
}
