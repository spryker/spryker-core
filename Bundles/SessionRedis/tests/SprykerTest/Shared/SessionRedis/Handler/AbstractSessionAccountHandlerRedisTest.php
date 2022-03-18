<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\SessionRedis\Handler;

use Codeception\Test\Unit;
use Spryker\Shared\SessionRedis\Handler\AbstractSessionAccountHandlerRedis;
use Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilder;
use Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilderInterface;
use Spryker\Shared\SessionRedis\Handler\LifeTime\SessionRedisLifeTimeCalculatorInterface;
use Spryker\Shared\SessionRedis\Hasher\BcryptHasher;
use Spryker\Shared\SessionRedis\Hasher\HasherInterface;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group SessionRedis
 * @group Handler
 * @group AbstractSessionAccountHandlerRedisTest
 * Add your own group annotations below this line
 */
class AbstractSessionAccountHandlerRedisTest extends Unit
{
    /**
     * @return void
     */
    public function testSaveCustomerSessionSuccessful(): void
    {
        // Arrange
        $idSession = 'idSession';
        $idAccount = 10;
        $sessionLifetime = 99;
        $hashedSessionId = 'hashedSessionId';

        $redisClient = $this->getMockBuilder(SessionRedisWrapperInterface::class)->getMock();
        $redisClient->expects(static::once())->method('setex')->with(
            "{$idAccount}:account_type:account",
            $sessionLifetime,
            $hashedSessionId,
        );

        $hasher = $this->getMockBuilder(HasherInterface::class)->getMock();
        $hasher->expects(static::once())->method('encrypt')->with($idSession)->willReturn($hashedSessionId);

        // Action
        $this->createSessionAccountHandler(
            $redisClient,
            $sessionLifetime,
            $hasher,
            new SessionKeyBuilder(),
        )->saveSessionAccount($idAccount, $idSession);
    }

    /**
     * @return void
     */
    public function testIsSessionAccountValidReturnsTrueWhenIdAccountNotFound(): void
    {
        // Arrange
        $idSession = 'idSession';
        $idAccount = 10;
        $sessionLifetime = 99;
        $redisClient = $this->getMockBuilder(SessionRedisWrapperInterface::class)->getMock();
        $redisClient->expects(static::once())->method('get')
            ->with(
                "$idAccount:account_type:account",
            )->willReturn(null);

        // Action
        $result = $this->createSessionAccountHandler(
            $redisClient,
            $sessionLifetime,
            new BcryptHasher(),
            new SessionKeyBuilder(),
        )->isSessionAccountValid($idAccount, $idSession);

        // Assert
        $this->assertTrue($result, 'Session id must be invalid.');
    }

    /**
     * @return void
     */
    public function testIsSessionAccountValidReturnsFalseWhenSessionIdInvalid(): void
    {
        // Arrange
        $idSession = 'idSession';
        $idAccount = 10;
        $sessionLifetime = 99;
        $redisClient = $this->getMockBuilder(SessionRedisWrapperInterface::class)->getMock();
        $redisClient->expects(static::once())->method('get')
            ->with(
                "{$idAccount}:account_type:account",
            )->willReturn('$2y$10$ERml.felur5NSFjFxgUl.uj4.QuMBl/Qx/bAtEqwy7LAKFksXGhpK');

        // Action
        $result = $this->createSessionAccountHandler(
            $redisClient,
            $sessionLifetime,
            new BcryptHasher(),
            new SessionKeyBuilder(),
        )->isSessionAccountValid($idAccount, $idSession);

        // Assert
        $this->assertTrue($result, 'Session id must be invalid.');
    }

    /**
     * @return void
     */
    public function testIsSessionAccountValidReturnsTrueId(): void
    {
        // Arrange
        $idSession = 'idSession';
        $idAccount = 10;
        $sessionLifetime = 99;
        $redisClient = $this->getMockBuilder(SessionRedisWrapperInterface::class)->getMock();
        $redisClient->expects(static::once())->method('get')
            ->with(
                "{$idAccount}:account_type:account",
            )->willReturn('invalid_value');

        // Action
        $result = $this->createSessionAccountHandler(
            $redisClient,
            $sessionLifetime,
            new BcryptHasher(),
            new SessionKeyBuilder(),
        )->isSessionAccountValid($idAccount, $idSession);

        // Assert
        $this->assertFalse($result, 'Session id must be invalid.');
    }

    /**
     * @param \Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface $redisClient
     * @param int $sessionLifetime
     * @param \Spryker\Shared\SessionRedis\Hasher\HasherInterface $hasher
     * @param \Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilderInterface $keyBuilder
     *
     * @return \Spryker\Shared\SessionRedis\Handler\AbstractSessionAccountHandlerRedis
     */
    protected function createSessionAccountHandler(
        SessionRedisWrapperInterface $redisClient,
        int $sessionLifetime,
        HasherInterface $hasher,
        SessionKeyBuilderInterface $keyBuilder
    ): AbstractSessionAccountHandlerRedis {
        $sessionRedisLifeTimeCalculator = $this->getMockBuilder(SessionRedisLifeTimeCalculatorInterface::class)->getMock();
        $sessionRedisLifeTimeCalculator->method('getSessionLifeTime')->willReturn($sessionLifetime);

        return new class ($redisClient, $sessionRedisLifeTimeCalculator, $hasher, $keyBuilder) extends AbstractSessionAccountHandlerRedis {
            /**
             * @return string
             */
            protected function getAccountType(): string
            {
                return 'account_type';
            }
        };
    }
}
