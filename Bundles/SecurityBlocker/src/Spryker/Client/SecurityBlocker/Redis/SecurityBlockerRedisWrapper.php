<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlocker\Redis;

use Spryker\Client\SecurityBlocker\Dependency\Client\SecurityBlockerToRedisClientInterface;
use Spryker\Client\SecurityBlocker\SecurityBlockerConfig;

class SecurityBlockerRedisWrapper implements SecurityBlockerRedisWrapperInterface
{
    protected const KV_PREFIX = 'kv:';

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

        $this->setupConnection();
    }

    /**
     * @param string $key
     *
     * @return string|null
     */
    public function get(string $key): ?string
    {
        return $this->redisClient->get(
            $this->securityBlockerConfig->getRedisConnectionKey(),
            $this->getStorageKey($key)
        );
    }

    /**
     * @param string $key
     *
     * @return int
     */
    public function incr(string $key): int
    {
        return $this->redisClient->incr(
            $this->securityBlockerConfig->getRedisConnectionKey(),
            $this->getStorageKey($key)
        );
    }

    /**
     * @param string $key
     * @param int $seconds
     * @param string $value
     *
     * @return bool
     */
    public function setex(string $key, int $seconds, string $value): bool
    {
        return $this->redisClient->setex(
            $this->securityBlockerConfig->getRedisConnectionKey(),
            $this->getStorageKey($key),
            $seconds,
            $value
        );
    }

    /**
     * @return void
     */
    protected function setupConnection(): void
    {
        $this->redisClient->setupConnection(
            $this->securityBlockerConfig->getRedisConnectionKey(),
            $this->securityBlockerConfig->getRedisConnectionConfiguration()
        );
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function getStorageKey(string $key = '*'): string
    {
        return static::KV_PREFIX . $key;
    }
}
