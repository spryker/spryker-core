<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Redis;

use Generated\Shared\Transfer\RedisConfigurationTransfer;
use Predis\Client;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Redis\RedisFactory getFactory()
 */
class RedisClient extends AbstractClient implements RedisClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $connectionKey
     * @param string $key
     *
     * @return string
     */
    public function get(string $connectionKey, string $key)
    {
        return $this->getConnection($connectionKey)->get($key);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $connectionKey
     * @param string $key
     * @param int $seconds
     * @param mixed $value
     *
     * @return int
     */
    public function setex(string $connectionKey, string $key, int $seconds, $value)
    {
        return $this->getConnection($connectionKey)->setex($key, $seconds, $value);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $connectionKey
     * @param string $key
     * @param mixed $value
     * @param mixed|null $expireResolution
     * @param int|null $expireTTL
     * @param mixed|null $flag
     *
     * @return mixed
     */
    public function set(string $connectionKey, string $key, $value, $expireResolution = null, ?int $expireTTL = null, $flag = null)
    {
        return $this->getConnection($connectionKey)->set($key, $value, $expireResolution, $expireTTL, $flag);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $connectionKey
     * @param array $keys
     *
     * @return int
     */
    public function del(string $connectionKey, array $keys)
    {
        return $this->getConnection($connectionKey)->del($keys);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $connectionKey
     * @param mixed $script
     * @param mixed $numkeys
     * @param mixed|null $keyOrArg1
     * @param mixed|null $keyOrArgN
     *
     * @return mixed
     */
    public function eval(string $connectionKey, $script, $numkeys, $keyOrArg1 = null, $keyOrArgN = null)
    {
        return $this->getConnection($connectionKey)->eval($script, $numkeys, $keyOrArg1, $keyOrArgN);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $connectionKey
     *
     * @return void
     */
    public function connect(string $connectionKey)
    {
        $this->getConnection($connectionKey)->connect();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $connectionKey
     *
     * @return void
     */
    public function disconnect(string $connectionKey)
    {
        $this->getConnection($connectionKey)->disconnect();
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $connectionKey
     * @param array $keys
     *
     * @return array
     */
    public function mget(string $connectionKey, array $keys)
    {
        return $this->getConnection($connectionKey)->mget($keys);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $connectionKey
     * @param array $dictionary
     *
     * @return mixed
     */
    public function mset(string $connectionKey, array $dictionary)
    {
        return $this->getConnection($connectionKey)->mset($dictionary);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $connectionKey
     * @param mixed|null $section
     *
     * @return array
     */
    public function info(string $connectionKey, $section = null)
    {
        return $this->getConnection($connectionKey)->info($section);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $connectionKey
     * @param mixed $pattern
     *
     * @return array
     */
    public function keys(string $connectionKey, $pattern)
    {
        return $this->getConnection($connectionKey)->keys($pattern);
    }

    /**
     * {@inheritdoc}
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
        $this->getFactory()->createConnectionProvider()->setupConnection($connectionKey, $configurationTransfer);
    }

    /**
     * @param string $connectionKey
     *
     * @return \Predis\Client
     */
    protected function getConnection(string $connectionKey): Client
    {
        return $this->getFactory()->createConnectionProvider()->getConnection($connectionKey);
    }
}
