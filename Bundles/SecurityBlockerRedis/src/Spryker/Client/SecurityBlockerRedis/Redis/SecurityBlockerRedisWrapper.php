<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlockerRedis\Redis;

use Generated\Shared\Transfer\AuthContextTransfer;
use Generated\Shared\Transfer\AuthResponseTransfer;
use Generated\Shared\Transfer\RedisConfigurationTransfer;
use Spryker\Client\SecurityBlockerRedis\Dependency\Client\SecurityBlockerRedisToRedisClientInterface;
use Spryker\Client\SecurityBlockerRedis\Exception\SecurityBlockerRedisException;
use Spryker\Client\SecurityBlockerRedis\SecurityBlockerRedisConfig;

class SecurityBlockerRedisWrapper implements SecurityBlockerRedisWrapperInterface
{
    protected const KV_PREFIX = 'kv:';
    protected const KEY_PART_SEPARATOR = ':';

    /**
     * @var \Spryker\Client\SecurityBlockerRedis\Dependency\Client\SecurityBlockerRedisToRedisClientInterface
     */
    protected $redisClient;

    /**
     * @var \Spryker\Client\SecurityBlockerRedis\SecurityBlockerRedisConfig
     */
    protected $securityBlockerRedisConfig;

    /**
     * @param \Spryker\Client\SecurityBlockerRedis\Dependency\Client\SecurityBlockerRedisToRedisClientInterface $redisClient
     * @param \Spryker\Client\SecurityBlockerRedis\SecurityBlockerRedisConfig $securityBlockerRedisConfig
     */
    public function __construct(
        SecurityBlockerRedisToRedisClientInterface $redisClient,
        SecurityBlockerRedisConfig $securityBlockerRedisConfig
    ) {
        $this->redisClient = $redisClient;
        $this->securityBlockerRedisConfig = $securityBlockerRedisConfig;

        $this->setupConnection($this->securityBlockerRedisConfig->getRedisConnectionConfiguration());
    }

    /**
     * @param \Generated\Shared\Transfer\AuthContextTransfer $authContextTransfer
     *
     * @throws \Spryker\Client\SecurityBlockerRedis\Exception\SecurityBlockerRedisException
     *
     * @return \Generated\Shared\Transfer\AuthResponseTransfer
     */
    public function logLoginAttempt(AuthContextTransfer $authContextTransfer): AuthResponseTransfer
    {
        $redisConnectionKey = $this->securityBlockerRedisConfig->getRedisConnectionKey();
        $key = $this->getKeyName($authContextTransfer);

        $existingValue = $this->redisClient->get($redisConnectionKey, $key);
        $existingValue = json_decode($existingValue, true);

        $newValue = ++$existingValue;

        if ($authContextTransfer->getTtl() === null) {
            $result = $this->redisClient->set($redisConnectionKey, $key, $newValue);
        } else {
            $result = $this->redisClient->setex($redisConnectionKey, $key, $authContextTransfer->getTtl(), $newValue);
        }

        if (!$result) {
            throw new SecurityBlockerRedisException(
                sprintf('Could not set redisKey: "%s" with existingValue: "%s"', $key, json_encode($existingValue))
            );
        }

        return (new AuthResponseTransfer())->fromArray($authContextTransfer->toArray(), true)
            ->setCount($newValue)
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\AuthContextTransfer $authContextTransfer
     *
     * @return \Generated\Shared\Transfer\AuthResponseTransfer
     */
    public function getLoginAttempt(AuthContextTransfer $authContextTransfer): AuthResponseTransfer
    {
        $key = $this->getKeyName($authContextTransfer);
        $value = $this->redisClient->get($this->securityBlockerRedisConfig->getRedisConnectionKey(), $key);

        $result = json_decode($value, true);

        if (json_last_error() === JSON_ERROR_SYNTAX) {
            return (new AuthResponseTransfer())->setIsSuccessful(false);
        }

        return (new AuthResponseTransfer())->fromArray($authContextTransfer->toArray(), true)
            ->setCount($result)
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $redisConfigurationTransfer
     *
     * @return void
     */
    protected function setupConnection(RedisConfigurationTransfer $redisConfigurationTransfer): void
    {
        $this->redisClient->setupConnection(
            $this->securityBlockerRedisConfig->getRedisConnectionKey(),
            $redisConfigurationTransfer
        );
    }

    /**
     * @param string $pattern
     *
     * @return string
     */
    protected function getSearchPattern(string $pattern = '*'): string
    {
        return static::KV_PREFIX . $pattern;
    }

    /**
     * @param \Generated\Shared\Transfer\AuthContextTransfer $authContextTransfer
     *
     * @return string
     */
    protected function getKeyName(AuthContextTransfer $authContextTransfer): string
    {
        return static::KV_PREFIX
            . $authContextTransfer->getIp()
            . static::KEY_PART_SEPARATOR
            . $authContextTransfer->getAccount();
    }
}
