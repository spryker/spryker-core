<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Redis\Adapter;

use Spryker\Client\Redis\Compressor\CompressorInterface;

class RedisCompressionAdapter implements RedisAdapterInterface
{
    /**
     * @param \Spryker\Client\Redis\Adapter\RedisAdapterInterface $redisAdapter
     * @param \Spryker\Client\Redis\Compressor\CompressorInterface $compressor
     */
    public function __construct(protected RedisAdapterInterface $redisAdapter, protected CompressorInterface $compressor)
    {
    }

    /**
     * @param string $key
     *
     * @return string|null
     */
    public function get(string $key): ?string
    {
        return $this->prepareSingleValueForGet($this->redisAdapter->get($key));
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
        return $this->redisAdapter->setex($key, $seconds, $this->prepareSingleValueForSet($value));
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
        return $this->redisAdapter->set($key, $this->prepareSingleValueForSet($value), $expireResolution, $expireTTL, $flag);
    }

    /**
     * @param array $keys
     *
     * @return int
     */
    public function del(array $keys): int
    {
        return $this->redisAdapter->del($keys);
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
        return $this->redisAdapter->eval($script, $numKeys, $keysOrArgs);
    }

    /**
     * @return void
     */
    public function connect(): void
    {
        $this->redisAdapter->connect();
    }

    /**
     * @return void
     */
    public function disconnect(): void
    {
        $this->redisAdapter->disconnect();
    }

    /**
     * @return bool
     */
    public function isConnected(): bool
    {
        return $this->redisAdapter->isConnected();
    }

    /**
     * @param array $keys
     *
     * @return array
     */
    public function mget(array $keys): array
    {
        return $this->prepereMultiValueForGet($this->redisAdapter->mget($keys));
    }

    /**
     * @param array $dictionary
     *
     * @return bool
     */
    public function mset(array $dictionary): bool
    {
        return $this->redisAdapter->mset($this->prepereMultiValueForSet($dictionary));
    }

    /**
     * @param string|null $section
     *
     * @return array
     */
    public function info(?string $section = null): array
    {
        return $this->redisAdapter->info($section);
    }

    /**
     * @param string $pattern
     *
     * @return array<string>
     */
    public function keys(string $pattern): array
    {
        return $this->redisAdapter->keys($pattern);
    }

    /**
     * @param int $cursor
     * @param array<string, mixed> $options
     *
     * @return array [string, string[]]
     */
    public function scan(int $cursor, array $options): array
    {
        return $this->redisAdapter->scan($cursor, $options);
    }

    /**
     * @return int
     */
    public function dbSize(): int
    {
        return $this->redisAdapter->dbSize();
    }

    /**
     * @return void
     */
    public function flushDb(): void
    {
        $this->redisAdapter->flushDb();
    }

    /**
     * @param string $key
     *
     * @return int
     */
    public function incr(string $key): int
    {
        return $this->redisAdapter->incr($key);
    }

    /**
     * @param string $data
     *
     * @return string
     */
    protected function prepareSingleValueForSet(string $data): string
    {
        if (!$this->compressor->canBeCompressed($data)) {
            return $data;
        }

        return $this->compressor->compress($data);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function prepereMultiValueForSet(array $data): array
    {
        foreach ($data as $key => $value) {
            if ($this->compressor->canBeCompressed($value)) {
                $data[$key] = $this->compressor->compress($value);
            }
        }

        return $data;
    }

    /**
     * @param string|null $data
     *
     * @return string|null
     */
    protected function prepareSingleValueForGet(?string $data): ?string
    {
        if ($data === null || !$this->compressor->isCompressed($data)) {
            return $data;
        }

        return $this->compressor->decompress($data);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function prepereMultiValueForGet(array $data): array
    {
        foreach ($data as $key => $value) {
            if ($value !== null && $this->compressor->isCompressed($value)) {
                $data[$key] = $this->compressor->decompress($value);
            }
        }

        return $data;
    }
}
