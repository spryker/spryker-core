<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Redis;

use Generated\Shared\Transfer\RedisConfigurationTransfer;
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\Redis\Adapter\RedisAdapterInterface;

/**
 * @method \Spryker\Client\Redis\RedisFactory getFactory()
 */
class RedisClient extends AbstractClient implements RedisClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $connectionKey
     * @param string $key
     *
     * @return string|null
     */
    public function get(string $connectionKey, string $key): ?string
    {
        return $this->getConnection($connectionKey)->get($key);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $connectionKey
     * @param string $key
     * @param int $seconds
     * @param string $value
     *
     * @return bool
     */
    public function setex(string $connectionKey, string $key, int $seconds, string $value): bool
    {
        return $this->getConnection($connectionKey)->setex($key, $seconds, $value);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $connectionKey
     * @param string $key
     * @param string $value
     * @param string|null $expireResolution
     * @param int|null $expireTTL
     * @param string|null $flag
     *
     * @return bool
     */
    public function set(string $connectionKey, string $key, string $value, ?string $expireResolution = null, ?int $expireTTL = null, ?string $flag = null): bool
    {
        return $this->getConnection($connectionKey)->set($key, $value, $expireResolution, $expireTTL, $flag);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $connectionKey
     * @param array $keys
     *
     * @return int
     */
    public function del(string $connectionKey, array $keys): int
    {
        return $this->getConnection($connectionKey)->del($keys);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $connectionKey
     * @param string $script
     * @param int $numKeys
     * @param array $keysOrArgs
     *
     * @return bool
     */
    public function eval(string $connectionKey, string $script, int $numKeys, ...$keysOrArgs): bool
    {
        return $this->getConnection($connectionKey)->eval($script, $numKeys, $keysOrArgs);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $connectionKey
     *
     * @return void
     */
    public function connect(string $connectionKey): void
    {
        $this->getConnection($connectionKey)->connect();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $connectionKey
     *
     * @return void
     */
    public function disconnect(string $connectionKey): void
    {
        $this->getConnection($connectionKey)->disconnect();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $connectionKey
     *
     * @return bool
     */
    public function isConnected(string $connectionKey): bool
    {
        return $this->getConnection($connectionKey)->isConnected();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $connectionKey
     * @param string[] $keys
     *
     * @return array
     */
    public function mget(string $connectionKey, array $keys): array
    {
        return $this->getConnection($connectionKey)->mget($keys);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $connectionKey
     * @param array $dictionary
     *
     * @return bool
     */
    public function mset(string $connectionKey, array $dictionary): bool
    {
        return $this->getConnection($connectionKey)->mset($dictionary);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $connectionKey
     * @param string|null $section
     *
     * @return array
     */
    public function info(string $connectionKey, ?string $section = null): array
    {
        return $this->getConnection($connectionKey)->info($section);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $connectionKey
     * @param string $pattern
     *
     * @return string[]
     */
    public function keys(string $connectionKey, string $pattern): array
    {
        return $this->getConnection($connectionKey)->keys($pattern);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $connectionKey
     * @param int $cursor
     * @param array $options
     *
     * @return array [string, string[]]
     */
    public function scan(string $connectionKey, int $cursor, array $options): array
    {
        return $this->getConnection($connectionKey)->scan($cursor, $options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $connectionKey
     *
     * @return int
     */
    public function dbSize(string $connectionKey): int
    {
        return $this->getConnection($connectionKey)->dbSize();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $connectionKey
     *
     * @return void
     */
    public function flushDb(string $connectionKey): void
    {
        $this->getConnection($connectionKey)->flushDb();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $connectionKey
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $configurationTransfer
     *
     * @return void
     */
    public function setupConnection(string $connectionKey, RedisConfigurationTransfer $configurationTransfer): void
    {
        $this->getFactory()->createRedisAdapterProvider()->setupConnection($connectionKey, $configurationTransfer);
    }

    /**
     * @param string $connectionKey
     *
     * @return \Spryker\Client\Redis\Adapter\RedisAdapterInterface
     */
    protected function getConnection(string $connectionKey): RedisAdapterInterface
    {
        return $this->getFactory()->createRedisAdapterProvider()->getAdapter($connectionKey);
    }
}
