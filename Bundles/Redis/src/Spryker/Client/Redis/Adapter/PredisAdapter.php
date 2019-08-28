<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Redis\Adapter;

use Predis\Client;

class PredisAdapter implements RedisAdapterInterface
{
    /**
     * @var \Predis\Client
     */
    protected $client;

    /**
     * @param \Predis\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $key
     *
     * @return string|null
     */
    public function get(string $key): ?string
    {
        return $this->client->get($key);
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
        return (bool)$this->client->setex($key, $seconds, $value);
    }

    /**
     * @param string $key
     * @param string $value
     * @param string|null $expireResolution
     * @param int|null $expireTTL
     * @param string|null $flag
     *
     * @return bool
     */
    public function set(string $key, string $value, ?string $expireResolution = null, ?int $expireTTL = null, ?string $flag = null): bool
    {
        return isset($expireTTL)
            ? (bool)$this->client->set($key, $value, $expireResolution, $expireTTL, $flag)
            : (bool)$this->client->set($key, $value);
    }

    /**
     * @param array $keys
     *
     * @return int
     */
    public function del(array $keys): int
    {
        return $this->client->del($keys);
    }

    /**
     * @param string $script
     * @param int $numKeys
     * @param array $keysOrArgs
     *
     * @return bool
     */
    public function eval(string $script, int $numKeys, $keysOrArgs): bool
    {
        return (bool)$this->client->eval($script, $numKeys, ...$keysOrArgs);
    }

    /**
     * @return void
     */
    public function connect(): void
    {
        $this->client->connect();
    }

    /**
     * @return void
     */
    public function disconnect(): void
    {
        $this->client->disconnect();
    }

    /**
     * @return bool
     */
    public function isConnected(): bool
    {
        return $this->client->isConnected();
    }

    /**
     * @param array $keys
     *
     * @return array
     */
    public function mget(array $keys): array
    {
        return $this->client->mget($keys);
    }

    /**
     * @param array $dictionary
     *
     * @return bool
     */
    public function mset(array $dictionary): bool
    {
        return (bool)$this->client->mset($dictionary);
    }

    /**
     * @param string|null $section
     *
     * @return array
     */
    public function info(?string $section = null): array
    {
        return $this->client->info($section);
    }

    /**
     * @param string $pattern
     *
     * @return string[]
     */
    public function keys(string $pattern): array
    {
        return $this->client->keys($pattern);
    }

    /**
     * @param int $cursor
     * @param array $options
     *
     * @return array [string, string[]]
     */
    public function scan(int $cursor, array $options): array
    {
        return $this->client->scan($cursor, $options);
    }

    /**
     * @return int
     */
    public function dbSize(): int
    {
        return $this->client->dbsize();
    }

    /**
     * @return void
     */
    public function flushDb(): void
    {
        $this->client->flushdb();
    }
}
