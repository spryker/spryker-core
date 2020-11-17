<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Redis\Adapter;

use Generated\Shared\Transfer\RedisConfigurationTransfer;
use Spryker\Shared\Redis\Logger\RedisLoggerInterface;

class LoggableRedisAdapter implements RedisAdapterInterface
{
    /**
     * @var \Spryker\Client\Redis\Adapter\RedisAdapterInterface
     */
    protected $redisAdapter;

    /**
     * @var \Spryker\Shared\Redis\Logger\RedisLoggerInterface
     */
    protected $redisLogger;

    /**
     * @var string
     */
    protected $dsn;

    /**
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $redisConfigurationTransfer
     * @param \Spryker\Client\Redis\Adapter\RedisAdapterInterface $redisAdapter
     * @param \Spryker\Shared\Redis\Logger\RedisLoggerInterface $redisLogger
     */
    public function __construct(RedisConfigurationTransfer $redisConfigurationTransfer, RedisAdapterInterface $redisAdapter, RedisLoggerInterface $redisLogger)
    {
        $this->setupDsn($redisConfigurationTransfer);
        $this->redisAdapter = $redisAdapter;
        $this->redisLogger = $redisLogger;
    }

    /**
     * @param string $key
     *
     * @return string|null
     */
    public function get(string $key): ?string
    {
        $result = call_user_func_array([$this->redisAdapter, 'get'], func_get_args());
        $this->redisLogger->logCall($this->dsn, 'GET', ['key' => $key], $result);

        return $result;
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
        $result = call_user_func_array([$this->redisAdapter, 'setex'], func_get_args());
        $this->redisLogger->logCall($this->dsn, 'SETEX', ['key' => $key, 'seconds' => $seconds, 'value' => $value], $result);

        return $result;
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
        $result = call_user_func_array([$this->redisAdapter, 'set'], func_get_args());
        $this->redisLogger->logCall(
            $this->dsn,
            'SET',
            [
                'key' => $key,
                'value' => $value,
                'expireResolution' => $expireResolution,
                'expireTTL' => $expireTTL,
                'flag' => $flag,
            ],
            $result
        );

        return $result;
    }

    /**
     * @param array $keys
     *
     * @return int
     */
    public function del(array $keys): int
    {
        $result = call_user_func_array([$this->redisAdapter, 'del'], func_get_args());
        $this->redisLogger->logCall($this->dsn, 'DEL', ['keys' => $keys], $result);

        return $result;
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
        $result = call_user_func_array([$this->redisAdapter, 'eval'], func_get_args());
        $this->redisLogger->logCall(
            $this->dsn,
            'EVAL',
            ['script' => $script, 'numKeys' => $numKeys, 'keysOrArgs' => $keysOrArgs],
            $result
        );

        return $result;
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
        $result = call_user_func_array([$this->redisAdapter, 'mget'], func_get_args());
        $this->redisLogger->logCall($this->dsn, 'MGET', ['keys' => $keys], $result);

        return $result;
    }

    /**
     * @param array $dictionary
     *
     * @return bool
     */
    public function mset(array $dictionary): bool
    {
        $result = call_user_func_array([$this->redisAdapter, 'mset'], func_get_args());
        $this->redisLogger->logCall($this->dsn, 'MSET', ['dictionary' => $dictionary], $result);

        return $result;
    }

    /**
     * @param string|null $section
     *
     * @return array
     */
    public function info(?string $section = null): array
    {
        $result = call_user_func_array([$this->redisAdapter, 'info'], func_get_args());
        $this->redisLogger->logCall($this->dsn, 'INFO', ['section' => $section], $result);

        return $result;
    }

    /**
     * @param string $pattern
     *
     * @return string[]
     */
    public function keys(string $pattern): array
    {
        $result = call_user_func_array([$this->redisAdapter, 'keys'], func_get_args());
        $this->redisLogger->logCall($this->dsn, 'KEYS', ['pattern' => $pattern], $result);

        return $result;
    }

    /**
     * @param int $cursor
     * @param array $options
     *
     * @return array [string, string[]]
     */
    public function scan(int $cursor, array $options): array
    {
        $result = call_user_func_array([$this->redisAdapter, 'scan'], func_get_args());
        $this->redisLogger->logCall($this->dsn, 'SCAN', ['cursor' => $cursor, 'options' => $options], $result);

        return $result;
    }

    /**
     * @return int
     */
    public function dbSize(): int
    {
        $result = $this->redisAdapter->dbSize();
        $this->redisLogger->logCall($this->dsn, 'DBSIZE', [], $result);

        return $result;
    }

    /**
     * @return void
     */
    public function flushDb(): void
    {
        $this->redisLogger->logCall($this->dsn, 'FLUSHDB', []);

        $this->redisAdapter->flushDb();
    }

    /**
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $redisConfigurationTransfer
     *
     * @return void
     */
    protected function setupDsn(RedisConfigurationTransfer $redisConfigurationTransfer)
    {
        $dataSourceNames = $redisConfigurationTransfer->getDataSourceNames();

        if ($dataSourceNames) {
            $this->dsn = implode(', ', $dataSourceNames);

            return;
        }

        $connectionCredentialsTransfer = $redisConfigurationTransfer->getConnectionCredentials();
        $dsn = '';

        if ($connectionCredentialsTransfer) {
            $dsn = sprintf(
                '%s://%s:%d/%s',
                $connectionCredentialsTransfer->getProtocol() ?? 'redis',
                $connectionCredentialsTransfer->getHost(),
                $connectionCredentialsTransfer->getPort(),
                $connectionCredentialsTransfer->getDatabase()
            );
        }

        $this->dsn = $dsn;
    }
}
