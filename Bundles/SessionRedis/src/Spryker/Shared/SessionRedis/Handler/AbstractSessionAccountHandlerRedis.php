<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis\Handler;

use Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilderInterface;
use Spryker\Shared\SessionRedis\Handler\LifeTime\SessionRedisLifeTimeCalculatorInterface;
use Spryker\Shared\SessionRedis\Hasher\HasherInterface;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface;

abstract class AbstractSessionAccountHandlerRedis implements SessionAccountHandlerRedisInterface
{
    /**
     * @var \Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface
     */
    protected $redisClient;

    /**
     * @var \Spryker\Shared\SessionRedis\Handler\LifeTime\SessionRedisLifeTimeCalculatorInterface
     */
    protected $sessionRedisLifeTimeCalculator;

    /**
     * @var \Spryker\Shared\SessionRedis\Hasher\HasherInterface
     */
    protected $hasher;

    /**
     * @var \Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilderInterface
     */
    protected $keyBuilder;

    /**
     * @param \Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface $redisClient
     * @param \Spryker\Shared\SessionRedis\Handler\LifeTime\SessionRedisLifeTimeCalculatorInterface $sessionRedisLifeTimeCalculator
     * @param \Spryker\Shared\SessionRedis\Hasher\HasherInterface $hasher
     * @param \Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilderInterface $keyBuilder
     */
    public function __construct(
        SessionRedisWrapperInterface $redisClient,
        SessionRedisLifeTimeCalculatorInterface $sessionRedisLifeTimeCalculator,
        HasherInterface $hasher,
        SessionKeyBuilderInterface $keyBuilder
    ) {
        $this->redisClient = $redisClient;
        $this->sessionRedisLifeTimeCalculator = $sessionRedisLifeTimeCalculator;
        $this->hasher = $hasher;
        $this->keyBuilder = $keyBuilder;
    }

    /**
     * @param int $idAccount
     * @param string $idSession
     *
     * @return void
     */
    public function saveSessionAccount(int $idAccount, string $idSession): void
    {
        $this->redisClient->setex(
            $this->keyBuilder->buildAccountKey($this->getAccountType(), (string)$idAccount),
            $this->sessionRedisLifeTimeCalculator->getSessionLifeTime(),
            $this->hasher->encrypt($idSession),
        );
    }

    /**
     * @param int $idAccount
     * @param string $idSession
     *
     * @return bool
     */
    public function isSessionAccountValid(int $idAccount, string $idSession): bool
    {
        $key = $this->keyBuilder->buildAccountKey($this->getAccountType(), (string)$idAccount);
        $hashedIdSession = $this->redisClient->get($key);

        if ($hashedIdSession === null) {
            return true;
        }

        return $this->hasher->validate($idSession, $hashedIdSession);
    }

    /**
     * @return string
     */
    abstract protected function getAccountType(): string;
}
