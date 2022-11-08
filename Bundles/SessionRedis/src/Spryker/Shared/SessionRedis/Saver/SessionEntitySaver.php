<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis\Saver;

use Generated\Shared\Transfer\SessionEntityRequestTransfer;
use Generated\Shared\Transfer\SessionEntityResponseTransfer;
use Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilderInterface;
use Spryker\Shared\SessionRedis\Handler\LifeTime\SessionRedisLifeTimeCalculatorInterface;
use Spryker\Shared\SessionRedis\Hasher\HasherInterface;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface;

class SessionEntitySaver implements SessionEntitySaverInterface
{
    /**
     * @var \Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface
     */
    protected SessionRedisWrapperInterface $redisClient;

    /**
     * @var \Spryker\Shared\SessionRedis\Handler\LifeTime\SessionRedisLifeTimeCalculatorInterface
     */
    protected SessionRedisLifeTimeCalculatorInterface $sessionRedisLifeTimeCalculator;

    /**
     * @var \Spryker\Shared\SessionRedis\Hasher\HasherInterface
     */
    protected HasherInterface $hasher;

    /**
     * @var \Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilderInterface
     */
    protected SessionKeyBuilderInterface $keyBuilder;

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
     * @param \Generated\Shared\Transfer\SessionEntityRequestTransfer $sessionEntityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SessionEntityResponseTransfer
     */
    public function save(SessionEntityRequestTransfer $sessionEntityRequestTransfer): SessionEntityResponseTransfer
    {
        $entityKey = $this->keyBuilder->buildEntityKey($sessionEntityRequestTransfer);

        $isSessionEntitySaved = $this->redisClient->setex(
            $entityKey,
            $this->sessionRedisLifeTimeCalculator->getSessionLifeTime(),
            $this->hasher->encrypt($sessionEntityRequestTransfer->getIdSessionOrFail()),
        );

        return (new SessionEntityResponseTransfer())
            ->setIsSuccessfull($isSessionEntitySaved);
    }
}
