<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SessionRedis\Communication\Lock;

use Codeception\Test\Unit;
use Spryker\Shared\SessionRedis\Handler\Lock\SessionLockerInterface;
use Spryker\Zed\SessionRedis\Communication\Lock\SessionLockReaderInterface;
use Spryker\Zed\SessionRedis\Communication\Lock\SessionLockReleaser;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group SessionRedis
 * @group Communication
 * @group Lock
 * @group SessionLockReleaserTest
 * Add your own group annotations below this line
 */
class SessionLockReleaserTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SessionRedis\Communication\Lock\SessionLockReaderInterface
     */
    protected $lockReaderMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\SessionRedis\Handler\Lock\SessionLockerInterface
     */
    protected $lockerMock;

    /**
     * @var \Spryker\Zed\SessionRedis\Communication\Lock\SessionLockReleaserInterface
     */
    protected $sessionLockReleaser;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->setupSessionLockerMock();
        $this->setupSessionLockReaderMock();

        $this->sessionLockReleaser = new SessionLockReleaser(
            $this->lockerMock,
            $this->lockReaderMock
        );
    }

    /**
     * @return void
     */
    public function testReleaseReturnsEarlyWhenLockTokenIsEmpty(): void
    {
        $this->lockReaderMock
            ->method('getTokenForSession')
            ->willReturn('');

        $this->lockerMock
            ->expects($this->never())
            ->method('unlock');

        $this->assertFalse($this->sessionLockReleaser->release('session_id'));
    }

    /**
     * @return void
     */
    public function testReleaseUnlocksSessionDataWhenTokenIsNotEmpty(): void
    {
        $sessionId = 'session_id';
        $lockToken = 'token';

        $this->lockReaderMock
            ->method('getTokenForSession')
            ->willReturn($lockToken);

        $this->lockerMock
            ->expects($this->once())
            ->method('unlock')
            ->with(
                $this->equalTo($sessionId),
                $this->equalTo($lockToken)
            )
            ->willReturn(true);

        $this->assertTrue($this->sessionLockReleaser->release($sessionId));
    }

    /**
     * @return void
     */
    protected function setupSessionLockReaderMock(): void
    {
        $this->lockReaderMock = $this->createMock(SessionLockReaderInterface::class);
    }

    /**
     * @return void
     */
    protected function setupSessionLockerMock(): void
    {
        $this->lockerMock = $this->createMock(SessionLockerInterface::class);
    }
}
