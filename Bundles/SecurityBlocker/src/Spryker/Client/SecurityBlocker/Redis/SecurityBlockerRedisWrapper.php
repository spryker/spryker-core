<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlocker\Redis;

use Generated\Shared\Transfer\AuthContextTransfer;
use Generated\Shared\Transfer\AuthResponseTransfer;
use Generated\Shared\Transfer\RedisConfigurationTransfer;
use Spryker\Client\SecurityBlocker\Dependency\Client\SecurityBlockerToRedisClientInterface;
use Spryker\Client\SecurityBlocker\Exception\SecurityBlockerException;
use Spryker\Client\SecurityBlocker\SecurityBlockerConfig;

class SecurityBlockerRedisWrapper implements SecurityBlockerRedisWrapperInterface
{
    protected const KV_PREFIX = 'kv:';
    protected const KEY_PART_SEPARATOR = ':';

    /**
     * @var \Spryker\Client\SecurityBlocker\Dependency\Client\SecurityBlockerToRedisClientInterface
     */
    protected $redisClient;

    /**
     * @var \Spryker\Client\SecurityBlocker\SecurityBlockerConfig
     */
    protected $securityBlockerConfig;

    /**
     * @param \Spryker\Client\SecurityBlocker\Dependency\Client\SecurityBlockerToRedisClientInterface $redisClient
     * @param \Spryker\Client\SecurityBlocker\SecurityBlockerConfig $securityBlockerConfig
     */
    public function __construct(
        SecurityBlockerToRedisClientInterface $redisClient,
        SecurityBlockerConfig $securityBlockerConfig
    ) {
        $this->redisClient = $redisClient;
        $this->securityBlockerConfig = $securityBlockerConfig;

        $this->setupConnection($this->securityBlockerConfig->getRedisConnectionConfiguration());
    }

    /**
     * @param \Generated\Shared\Transfer\AuthContextTransfer $authContextTransfer
     *
     * @throws \Spryker\Client\SecurityBlocker\Exception\SecurityBlockerException
     *
     * @return \Generated\Shared\Transfer\AuthResponseTransfer
     */
    public function logLoginAttempt(AuthContextTransfer $authContextTransfer): AuthResponseTransfer
    {
        $redisConnectionKey = $this->securityBlockerConfig->getRedisConnectionKey();
        $key = $this->getKeyName($authContextTransfer);

        $existingValue = $this->redisClient->get($redisConnectionKey, $key);
        $existingValue = json_decode($existingValue, true);

        $newValue = ++$existingValue;

        // use `incr` for existing keys.
        // ttl will reset each write now.
        if ($authContextTransfer->getTtl() === null) {
            $result = $this->redisClient->set($redisConnectionKey, $key, (string)$newValue);
        } else {
            $result = $this->redisClient->setex($redisConnectionKey, $key, $authContextTransfer->getTtl(), (string)$newValue);
        }

        if (!$result) {
            throw new SecurityBlockerException(
                sprintf('Could not set redisKey: "%s" with existingValue: "%s"', $key, json_encode($existingValue))
            );
        }

        return (new AuthResponseTransfer())->fromArray($authContextTransfer->toArray(), true)
            ->setCount((int)$newValue)
            ->setIsSuccessful($newValue < 5);
    }

    /**
     * @param \Generated\Shared\Transfer\AuthContextTransfer $authContextTransfer
     *
     * @return \Generated\Shared\Transfer\AuthResponseTransfer
     */
    public function getLoginAttempt(AuthContextTransfer $authContextTransfer): AuthResponseTransfer
    {
        $key = $this->getKeyName($authContextTransfer);
        $value = $this->redisClient->get($this->securityBlockerConfig->getRedisConnectionKey(), $key);

        $result = json_decode($value, true);

        if (json_last_error() === JSON_ERROR_SYNTAX) {
            return (new AuthResponseTransfer())->setIsSuccessful(false);
        }

        return (new AuthResponseTransfer())->fromArray($authContextTransfer->toArray(), true)
            ->setCount($result)
            ->setIsSuccessful($result < 5);
    }

    /**
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $redisConfigurationTransfer
     *
     * @return void
     */
    protected function setupConnection(RedisConfigurationTransfer $redisConfigurationTransfer): void
    {
        $this->redisClient->setupConnection(
            $this->securityBlockerConfig->getRedisConnectionKey(),
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
