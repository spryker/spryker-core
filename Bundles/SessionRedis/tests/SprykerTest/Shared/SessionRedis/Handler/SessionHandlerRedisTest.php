<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\SessionRedis\Handler;

use Codeception\Test\Unit;
use Spryker\Shared\SessionRedis\Dependency\Service\SessionRedisToMonitoringServiceInterface;
use Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilder;
use Spryker\Shared\SessionRedis\Handler\SessionHandlerRedis;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Shared
 * @group SessionRedis
 * @group Handler
 * @group SessionHandlerRedisTest
 * Add your own group annotations below this line
 */
class SessionHandlerRedisTest extends Unit
{
    protected const SESSION_LIFETIME = 60;

    /**
     * @var \Spryker\Shared\SessionRedis\Handler\SessionHandlerRedis
     */
    protected $sessionHandler;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface
     */
    protected $redisClientMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\SessionRedis\Dependency\Service\SessionRedisToMonitoringServiceInterface
     */
    protected $monitoringServiceMock;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->setupRedisClientMock();
        $this->setupMonitoringServiceMock();
        $this->setupSessionHandler();
    }

    /**
     * @return void
     */
    public function canConnectToRedisWhenSessionIsOpen(): void
    {
        $this->redisClientMock
            ->expects($this->once())
            ->method('connect');

        $this->assertTrue($this->sessionHandler->open('save path', 'session name'));
    }

    /**
     * @return void
     */
    public function testCanDisconnectFromRedisWhenSessionIsClosed(): void
    {
        $this->redisClientMock
            ->expects($this->once())
            ->method('disconnect');

        $this->assertTrue($this->sessionHandler->close());
    }

    /**
     * @return void
     */
    public function testCanReadEmptyData(): void
    {
        $this->redisClientMock
            ->expects($this->once())
            ->method('get')
            ->willReturn(null);

        $this->assertEquals('', $this->sessionHandler->read('save id'));
    }

    /**
     * @return void
     */
    public function testCanReadNonEmptyData(): void
    {
        $this->redisClientMock
            ->expects($this->once())
            ->method('get')
            ->willReturn('"data"');

        $this->assertEquals('data', $this->sessionHandler->read('save id'));
    }

    /**
     * @return void
     */
    public function testGcReturnsTrue(): void
    {
        $this->assertTrue($this->sessionHandler->gc(1));
    }

    /**
     * @return void
     */
    public function testCatDestroySession(): void
    {
        $this->redisClientMock
            ->expects($this->once())
            ->method('del')
            ->with(['session:session_id']);

        $this->sessionHandler->destroy('session_id');
    }

    /**
     * @return void
     */
    public function testWriterReturnsTrueWhenDataIsEmpty(): void
    {
        $this->assertTrue($this->sessionHandler->write('session_id', ''));
    }

    /**
     * @return void
     */
    public function testCanWriteExpirableData(): void
    {
        $this->redisClientMock
            ->expects($this->once())
            ->method('setex')
            ->with(
                'session:session_id',
                static::SESSION_LIFETIME,
                json_encode('data')
            )
            ->willReturn(true);

        $this->assertTrue($this->sessionHandler->write('session_id', 'data'));
    }

    /**
     * @return void
     */
    protected function setupRedisClientMock(): void
    {
        $this->redisClientMock = $this->getMockBuilder(SessionRedisWrapperInterface::class)
            ->getMock();
    }

    /**
     * @return void
     */
    protected function setupMonitoringServiceMock(): void
    {
        $this->monitoringServiceMock = $this->getMockBuilder(SessionRedisToMonitoringServiceInterface::class)
            ->getMock();
    }

    /**
     * @return void
     */
    protected function setupSessionHandler(): void
    {
        $this->sessionHandler = new SessionHandlerRedis(
            $this->redisClientMock,
            new SessionKeyBuilder(),
            $this->monitoringServiceMock,
            static::SESSION_LIFETIME
        );
    }
}
