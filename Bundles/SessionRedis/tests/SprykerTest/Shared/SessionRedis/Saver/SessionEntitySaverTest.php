<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\SessionRedis\Saver;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SessionEntityRequestTransfer;
use Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilder;
use Spryker\Shared\SessionRedis\Handler\LifeTime\SessionRedisLifeTimeCalculatorInterface;
use Spryker\Shared\SessionRedis\Hasher\BcryptHasher;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface;
use Spryker\Shared\SessionRedis\Saver\SessionEntitySaver;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group SessionRedis
 * @group Saver
 * @group SessionEntitySaverTest
 * Add your own group annotations below this line
 */
class SessionEntitySaverTest extends Unit
{
    /**
     * @uses \Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilder::SESSION_ENTITY_KEY_SUFFIX
     *
     * @var string
     */
    protected const SESSION_ENTITY_KEY_SUFFIX = 'entity';

    /**
     * @var string
     */
    protected const TEST_ENTITY_TYPE = 'test';

    /**
     * @var int
     */
    protected const TEST_ID_ENTITY = 1;

    /**
     * @var string
     */
    protected const TEST_ID_SESSION = 'testSession';

    /**
     * @var int
     */
    protected const TEST_SESSION_LIFETIME = 90;

    /**
     * @return void
     */
    public function testSaveShouldNotReturnSuccessWhenSessionNotSaved(): void
    {
        // Arrange
        $sessionRedisWrapperMock = $this->createSessionRedisWrapperMock();
        $sessionRedisWrapperMock->expects($this->once())
            ->method('setex')
            ->willReturn(false);

        $sessionEntitySaver = new SessionEntitySaver(
            $sessionRedisWrapperMock,
            $this->createSessionRedisLifeTimeCalculatorMock(static::TEST_SESSION_LIFETIME),
            new BcryptHasher(),
            new SessionKeyBuilder(),
        );

        // Act
        $sessionEntityResponseTransfer = $sessionEntitySaver->save(
            $this->getSessionEntityRequest(),
        );

        // Assert
        $this->assertFalse($sessionEntityResponseTransfer->getIsSuccessfull());
    }

    /**
     * @return void
     */
    public function testSaveShouldReturnSuccessWhenSessionIsSaved(): void
    {
        // Arrange
        $sessionRedisWrapperMock = $this->createSessionRedisWrapperMock();
        $sessionRedisWrapperMock->expects($this->once())
            ->method('setex')
            ->willReturn(true);

        $sessionEntitySaver = new SessionEntitySaver(
            $sessionRedisWrapperMock,
            $this->createSessionRedisLifeTimeCalculatorMock(static::TEST_SESSION_LIFETIME),
            new BcryptHasher(),
            new SessionKeyBuilder(),
        );

        // Act
        $sessionEntityResponseTransfer = $sessionEntitySaver->save(
            $this->getSessionEntityRequest(),
        );

        // Assert
        $this->assertTrue($sessionEntityResponseTransfer->getIsSuccessfull());
    }

    /**
     * @return \Generated\Shared\Transfer\SessionEntityRequestTransfer
     */
    protected function getSessionEntityRequest(): SessionEntityRequestTransfer
    {
        return (new SessionEntityRequestTransfer())->fromArray([
            SessionEntityRequestTransfer::ENTITY_TYPE => static::TEST_ENTITY_TYPE,
            SessionEntityRequestTransfer::ID_ENTITY => static::TEST_ENTITY_TYPE,
            SessionEntityRequestTransfer::ID_SESSION => static::TEST_ID_SESSION,
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\SessionEntityRequestTransfer $sessionEntityRequestTransfer
     *
     * @return string
     */
    protected function buildSessionEntityKey(SessionEntityRequestTransfer $sessionEntityRequestTransfer): string
    {
        return sprintf(
            '%s:%s:%s',
            $sessionEntityRequestTransfer->getIdEntityOrFail(),
            $sessionEntityRequestTransfer->getEntityTypeOrFail(),
            static::SESSION_ENTITY_KEY_SUFFIX,
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface
     */
    protected function createSessionRedisWrapperMock(): SessionRedisWrapperInterface
    {
        return $this->getMockBuilder(SessionRedisWrapperInterface::class)
            ->getMock();
    }

    /**
     * @param int $sessionLifeTime
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\SessionRedis\Handler\LifeTime\SessionRedisLifeTimeCalculatorInterface
     */
    protected function createSessionRedisLifeTimeCalculatorMock(int $sessionLifeTime): SessionRedisLifeTimeCalculatorInterface
    {
        $sessionRedisLifeTimeCalculatorMock = $this->getMockBuilder(SessionRedisLifeTimeCalculatorInterface::class)
            ->getMock();

        $sessionRedisLifeTimeCalculatorMock->method('getSessionLifeTime')
            ->willReturn($sessionLifeTime);

        return $sessionRedisLifeTimeCalculatorMock;
    }
}
