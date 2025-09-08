<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Lock\Business\PersistingStore;

use Spryker\Zed\Lock\Dependency\Client\LockToStorageRedisClientInterface;
use Spryker\Zed\Lock\LockConfig;
use Symfony\Component\Lock\Exception\LockConflictedException;
use Symfony\Component\Lock\Exception\LockStorageException;
use Symfony\Component\Lock\Key;
use Symfony\Component\Lock\SharedLockStoreInterface;
use Symfony\Component\Lock\Store\ExpiringStoreTrait;
use Throwable;

/**
 * Eval scripts were copied from Symfony\Component\Lock\Store\RedisStore with some adjustments. Extending class doesn't work due to the fact that it uses private methods.
 */
class RedisStore implements SharedLockStoreInterface
{
    use ExpiringStoreTrait;

    /**
     * @var float
     */
    protected float $initialTtl = 300.0;

    /**
     * @param \Spryker\Zed\Lock\Dependency\Client\LockToStorageRedisClientInterface $redis
     * @param \Spryker\Zed\Lock\LockConfig $config
     */
    public function __construct(
        protected LockToStorageRedisClientInterface $redis,
        protected LockConfig $config
    ) {
        $this->initialTtl = $config->getStorageInitialTtl();
    }

    /**
     * @param \Symfony\Component\Lock\Key $key
     *
     * @throws \Symfony\Component\Lock\Exception\LockConflictedException
     *
     * @return void
     */
    public function save(Key $key): void
    {
        $script = '
            local key = KEYS[1]
            local uniqueToken = ARGV[2]
            local ttl = tonumber(ARGV[3])

            local now = redis.call("TIME")
            now = now[1] * 1000 + math.floor(now[2] / 1000)

            -- Remove expired values
            redis.call("ZREMRANGEBYSCORE", key, "-inf", now)

            -- is already acquired
            if redis.call("ZSCORE", key, uniqueToken) then
                -- is not WRITE lock and cannot be promoted
                if not redis.call("ZSCORE", key, "__write__") and redis.call("ZCOUNT", key, "-inf", "+inf") > 1  then
                    return false
                end
            elseif redis.call("ZCOUNT", key, "-inf", "+inf") > 0  then
                return false
            end

            redis.call("ZADD", key, now + ttl, uniqueToken)
            redis.call("ZADD", key, now + ttl, "__write__")

            -- Extend the TTL of the key
            local maxExpiration = redis.call("ZREVRANGE", key, 0, 0, "WITHSCORES")[2]
            redis.call("PEXPIREAT", key, maxExpiration)

            return true
        ';

        $key->reduceLifetime($this->initialTtl);
        if (!$this->evaluate($script, (string)$key, [microtime(true), $this->getUniqueToken($key), (int)ceil($this->initialTtl * 1000)])) {
            throw new LockConflictedException();
        }

        $this->checkNotExpired($key);
    }

    /**
     * @param \Symfony\Component\Lock\Key $key
     *
     * @throws \Symfony\Component\Lock\Exception\LockConflictedException
     *
     * @return void
     */
    public function saveRead(Key $key): void
    {
        $script = '
            local key = KEYS[1]
            local uniqueToken = ARGV[2]
            local ttl = tonumber(ARGV[3])

            local now = redis.call("TIME")
            now = now[1] * 1000 + math.floor(now[2] / 1000)

            -- Remove expired values
            redis.call("ZREMRANGEBYSCORE", key, "-inf", now)

            -- lock not already acquired and a WRITE lock exists?
            if not redis.call("ZSCORE", key, uniqueToken) and redis.call("ZSCORE", key, "__write__") then
                return false
            end

            redis.call("ZADD", key, now + ttl, uniqueToken)
            redis.call("ZREM", key, "__write__")

            -- Extend the TTL of the key
            local maxExpiration = redis.call("ZREVRANGE", key, 0, 0, "WITHSCORES")[2]
            redis.call("PEXPIREAT", key, maxExpiration)

            return true
        ';

        $key->reduceLifetime($this->initialTtl);
        if (!$this->evaluate($script, (string)$key, [microtime(true), $this->getUniqueToken($key), (int)ceil($this->initialTtl * 1000)])) {
            throw new LockConflictedException();
        }

        $this->checkNotExpired($key);
    }

    /**
     * @param \Symfony\Component\Lock\Key $key
     * @param float $ttl
     *
     * @throws \Symfony\Component\Lock\Exception\LockConflictedException
     *
     * @return void
     */
    public function putOffExpiration(Key $key, float $ttl): void
    {
        $script = '
            local key = KEYS[1]
            local uniqueToken = ARGV[2]
            local ttl = tonumber(ARGV[3])

            local now = redis.call("TIME")
            now = now[1] * 1000 + math.floor(now[2] / 1000)

            -- lock already acquired acquired?
            if not redis.call("ZSCORE", key, uniqueToken) then
                return false
            end

            redis.call("ZADD", key, now + ttl, uniqueToken)
            -- if the lock is also a WRITE lock, increase the TTL
            if redis.call("ZSCORE", key, "__write__") then
                redis.call("ZADD", key, now + ttl, "__write__")
            end

            -- Extend the TTL of the key
            local maxExpiration = redis.call("ZREVRANGE", key, 0, 0, "WITHSCORES")[2]
            redis.call("PEXPIREAT", key, maxExpiration)

            return true
        ';

        $key->reduceLifetime($ttl);
        if (!$this->evaluate($script, (string)$key, [microtime(true), $this->getUniqueToken($key), (int)ceil($ttl * 1000)])) {
            throw new LockConflictedException();
        }

        $this->checkNotExpired($key);
    }

    /**
     * @param \Symfony\Component\Lock\Key $key
     *
     * @return void
     */
    public function delete(Key $key): void
    {
        $script = '
            local key = KEYS[1]
            local uniqueToken = ARGV[1]

            -- lock not already acquired
            if not redis.call("ZSCORE", key, uniqueToken) then
                return false
            end

            redis.call("ZREM", key, uniqueToken)
            redis.call("ZREM", key, "__write__")

            local maxExpiration = redis.call("ZREVRANGE", key, 0, 0, "WITHSCORES")[2]
            if nil ~= maxExpiration then
                redis.call("PEXPIREAT", key, maxExpiration)
            end

            return true
        ';

        $this->evaluate($script, (string)$key, [$this->getUniqueToken($key)]);
    }

    /**
     * @param \Symfony\Component\Lock\Key $key
     *
     * @return bool
     */
    public function exists(Key $key): bool
    {
        $script = '
            local key = KEYS[1]
            local uniqueToken = ARGV[2]

            local now = redis.call("TIME")
            now = now[1] * 1000 + math.floor(now[2] / 1000)

            -- Remove expired values
            redis.call("ZREMRANGEBYSCORE", key, "-inf", now)

            if redis.call("ZSCORE", key, uniqueToken) then
                return true
            end

            return false
        ';

        return $this->evaluate($script, (string)$key, [microtime(true), $this->getUniqueToken($key)]);
    }

    /**
     * @param \Symfony\Component\Lock\Key $key
     *
     * @return string
     */
    protected function getUniqueToken(Key $key): string
    {
        if (!$key->hasState(static::class)) {
            $token = base64_encode(random_bytes(32));
            $key->setState(static::class, $token);
        }

        return $key->getState(static::class);
    }

    /**
     * @param string $script
     * @param string $resource
     * @param array<mixed> $args
     *
     * @throws \Symfony\Component\Lock\Exception\LockStorageException
     *
     * @return bool
     */
    protected function evaluate(string $script, string $resource, array $args): bool
    {
        try {
            return $this->redis->evaluate($script, 1, $resource, ...$args);
        } catch (Throwable $e) {
            throw new LockStorageException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
