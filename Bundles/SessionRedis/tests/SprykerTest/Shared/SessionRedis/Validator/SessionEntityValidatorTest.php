<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\SessionRedis\Validator;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SessionEntityRequestTransfer;
use Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilder;
use Spryker\Shared\SessionRedis\Hasher\BcryptHasher;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface;
use Spryker\Shared\SessionRedis\Validator\SessionEntityValidator;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group SessionRedis
 * @group Validator
 * @group SessionEntityValidatorTest
 * Add your own group annotations below this line
 */
class SessionEntityValidatorTest extends Unit
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
     * @var string
     */
    protected const TEST_ID_SESSION = 'testSession';

    /**
     * @return void
     */
    public function testValidateShouldReturnTrueWhenDataIsNotFound(): void
    {
        // Arrange
        $sessionEntityRequestTransfer = $this->getSessionEntityRequest();

        $sessionRedisWrapperMock = $this->createSessionRedisWrapperMock();
        $sessionRedisWrapperMock->method('get')
            ->with($this->buildSessionEntityKey($sessionEntityRequestTransfer))
            ->willReturn(null);

        $sessionEntityValidator = new SessionEntityValidator(
            $sessionRedisWrapperMock,
            new BcryptHasher(),
            new SessionKeyBuilder(),
        );

        // Act
        $sessionEntityResponseTransfer = $sessionEntityValidator->validate($sessionEntityRequestTransfer);

        // Assert
        $this->assertTrue($sessionEntityResponseTransfer->getIsSuccessfull());
    }

    /**
     * @return void
     */
    public function testValidateShouldReturnFalseWhenIdSessionIsInvalid(): void
    {
        // Arrange
        $sessionEntityRequestTransfer = $this->getSessionEntityRequest();

        $sessionRedisWrapperMock = $this->createSessionRedisWrapperMock();
        $sessionRedisWrapperMock->method('get')
            ->with($this->buildSessionEntityKey($sessionEntityRequestTransfer))
            ->willReturn('invalid');

        $sessionEntityValidator = new SessionEntityValidator(
            $sessionRedisWrapperMock,
            new BcryptHasher(),
            new SessionKeyBuilder(),
        );

        // Action
        $sessionEntityResponseTransfer = $sessionEntityValidator->validate($sessionEntityRequestTransfer);

        // Assert
        $this->assertFalse($sessionEntityResponseTransfer->getIsSuccessfull());
    }

    /**
     * @return void
     */
    public function testValidateReturnsTrueId(): void
    {
        // Arrange
        $sessionEntityRequestTransfer = $this->getSessionEntityRequest();

        $sessionRedisWrapperMock = $this->createSessionRedisWrapperMock();
        $sessionRedisWrapperMock->method('get')
            ->with($this->buildSessionEntityKey($sessionEntityRequestTransfer))
            ->willReturn('$2y$10$k0g/od6o0WlDguvaBoQGe.vG2Ersfap5ZX8DhD6BMAZFOSvE7HHkG');

        $sessionEntityValidator = new SessionEntityValidator(
            $sessionRedisWrapperMock,
            new BcryptHasher(),
            new SessionKeyBuilder(),
        );

        // Action
        $sessionEntityResponseTransfer = $sessionEntityValidator->validate($sessionEntityRequestTransfer);

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
}
