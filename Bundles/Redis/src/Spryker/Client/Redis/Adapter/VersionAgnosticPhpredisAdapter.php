<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Redis\Adapter;

use Redis;

class VersionAgnosticPhpredisAdapter implements RedisAdapterInterface
{
    /**
     * @param \Redis $client
     */
    public function __construct(protected Redis $client)
    {
    }

    /**
     * @param string $key
     *
     * @return string|null
     */
    public function get(string $key): ?string
    {
        $value = $this->client->get($key);

        return $value === false ? null : $value;
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
        $options = [];

        if ($expireResolution) {
            $options[$expireResolution] = $expireTTL;
        }

        if ($flag) {
            $options[] = $flag;
        }

        return (bool)$this->client->set($key, $value, $options);
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
    public function eval(string $script, int $numKeys, array $keysOrArgs): bool
    {
        $result = $this->client->eval($script, $keysOrArgs, $numKeys);

        return $result !== false;
    }

    /**
     * @return void
     */
    public function connect(): void
    {
        $this->client->connect(
            $this->client->getHost(),
            $this->client->getPort(),
            (float)$this->client->getTimeout(),
            $this->client->getPersistentID(),
            0,
            $this->client->getReadTimeout(),
        );
    }

    /**
     * @return void
     */
    public function disconnect(): void
    {
        $this->client->close();
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
        $values = $this->client->mget($keys);

        $values = array_map(function ($v) {
            return ($v !== false) ? $v : null;
        }, $values);

        return $values;
    }

    /**
     * @param string|null $section
     *
     * @return array
     */
    public function info(?string $section = null): array
    {
        $info = $this->client->info($section); //@phpstan-ignore-line

        return $info !== false ? $info : [];
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
     * @param string $pattern
     *
     * @return array<string>
     */
    public function keys(string $pattern): array
    {
        $keys = $this->client->keys($pattern);

        return $keys !== false ? $keys : [];
    }

    /**
     * @param int $cursor
     * @param array<string, mixed> $options
     *
     * @return array [string, string[]]
     */
    public function scan(int $cursor, array $options): array
    {
        $pattern = $options['pattern'] ?? null;
        $count = $options['count'] ?? null;
        if ($cursor === 0) {
            $cursor = null;
        }

        $result = $this->client->scan($cursor, $pattern, $count);

        if ($result === false) {
            return [$cursor, []];
        }

        return [$cursor, $result];
    }

    /**
     * @return int
     */
    public function dbSize(): int
    {
        $size = $this->client->dbSize();

        return $size !== false ? $size : 0;
    }

    /**
     * @return void
     */
    public function flushDb(): void
    {
        $this->client->flushDB();
    }

    /**
     * @param string $key
     *
     * @return int
     */
    public function incr(string $key): int
    {
        $result = $this->client->incr($key);

        return $result !== false ? $result : 0;
    }
}
