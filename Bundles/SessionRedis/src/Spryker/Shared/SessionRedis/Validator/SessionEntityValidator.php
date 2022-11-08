<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis\Validator;

use Generated\Shared\Transfer\SessionEntityRequestTransfer;
use Generated\Shared\Transfer\SessionEntityResponseTransfer;
use Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilderInterface;
use Spryker\Shared\SessionRedis\Hasher\HasherInterface;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface;

class SessionEntityValidator implements SessionEntityValidatorInterface
{
    /**
     * @var \Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface
     */
    protected SessionRedisWrapperInterface $redisClient;

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
     * @param \Spryker\Shared\SessionRedis\Hasher\HasherInterface $hasher
     * @param \Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilderInterface $keyBuilder
     */
    public function __construct(
        SessionRedisWrapperInterface $redisClient,
        HasherInterface $hasher,
        SessionKeyBuilderInterface $keyBuilder
    ) {
        $this->redisClient = $redisClient;
        $this->hasher = $hasher;
        $this->keyBuilder = $keyBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\SessionEntityRequestTransfer $sessionEntityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SessionEntityResponseTransfer
     */
    public function validate(SessionEntityRequestTransfer $sessionEntityRequestTransfer): SessionEntityResponseTransfer
    {
        $sessionEntityResponseTransfer = (new SessionEntityResponseTransfer())->setIsSuccessfull(true);

        $entityKey = $this->keyBuilder->buildEntityKey($sessionEntityRequestTransfer);

        $hashedIdSession = $this->redisClient->get($entityKey);
        if ($hashedIdSession === null) {
            return $sessionEntityResponseTransfer;
        }

        return $sessionEntityResponseTransfer->setIsSuccessfull(
            $this->hasher->validate($sessionEntityRequestTransfer->getIdSessionOrFail(), $hashedIdSession),
        );
    }
}
