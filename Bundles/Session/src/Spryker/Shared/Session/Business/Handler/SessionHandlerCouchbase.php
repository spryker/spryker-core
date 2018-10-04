<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Session\Business\Handler;

use Couchbase;
use SessionHandlerInterface;
use Spryker\Shared\NewRelicApi\NewRelicApiInterface;

class SessionHandlerCouchbase implements SessionHandlerInterface
{
    public const METRIC_SESSION_DELETE_TIME = 'Couchbase/Session_delete_time';
    public const METRIC_SESSION_WRITE_TIME = 'Couchbase/Session_write_time';
    public const METRIC_SESSION_READ_TIME = 'Couchbase/Session_read_time';

    /**
     * @var \Couchbase
     */
    protected $connection;

    /**
     * e.g. ['127.0.0.1:8091']
     *
     * @var array
     */
    protected $hosts = [];

    /**
     * @var string
     */
    protected $user;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $bucketName;

    /**
     * @var bool
     */
    protected $persistent;

    /**
     * @var string
     */
    protected $keyPrefix = 'session:';

    /**
     * @var int
     */
    protected $lifetime;

    /**
     * @var \Spryker\Shared\NewRelicApi\NewRelicApiInterface
     */
    protected $newRelicApi;

    /**
     * @param \Spryker\Shared\NewRelicApi\NewRelicApiInterface $newRelicApi
     * @param array $hosts
     * @param string|null $user
     * @param string|null $password
     * @param string $bucketName
     * @param bool $persistent
     * @param int $lifetime
     */
    public function __construct(
        NewRelicApiInterface $newRelicApi,
        $hosts = ['127.0.0.1:8091'],
        $user = null,
        $password = null,
        $bucketName = 'default',
        $persistent = true,
        $lifetime = 600
    ) {
        $this->newRelicApi = $newRelicApi;
        $this->hosts = $hosts;
        $this->user = $user;
        $this->password = $password;
        $this->bucketName = $bucketName;
        $this->persistent = $persistent;
        $this->lifetime = $lifetime;
    }

    /**
     * @param string $savePath
     * @param string $sessionName
     *
     * @return bool
     */
    public function open($savePath, $sessionName)
    {
        $this->connection = new Couchbase(
            $this->hosts,
            $this->user,
            $this->password,
            $this->bucketName,
            $this->persistent
        );

        return $this->connection ? true : false;
    }

    /**
     * @return bool
     */
    public function close()
    {
        unset($this->connection);

        return true;
    }

    /**
     * @param string $sessionId
     *
     * @return string|null
     */
    public function read($sessionId)
    {
        $key = $this->keyPrefix . $sessionId;

        $startTime = microtime(true);
        $result = $this->connection->getAndTouch($key, $this->lifetime);
        $this->newRelicApi->addCustomMetric(self::METRIC_SESSION_READ_TIME, microtime(true) - $startTime);

        return $result ? json_decode($result, true) : '';
    }

    /**
     * @param string $sessionId
     * @param string $sessionData
     *
     * @return bool
     */
    public function write($sessionId, $sessionData)
    {
        $key = $this->keyPrefix . $sessionId;

        if (strlen($sessionData) < 1) {
            return false;
        }

        $startTime = microtime(true);
        $result = $this->connection->set($key, json_encode($sessionData), $this->lifetime);
        $this->newRelicApi->addCustomMetric(self::METRIC_SESSION_WRITE_TIME, microtime(true) - $startTime);

        return $result ? true : false;
    }

    /**
     * @param int|string $sessionId
     *
     * @return bool
     */
    public function destroy($sessionId)
    {
        $key = $this->keyPrefix . $sessionId;

        $startTime = microtime(true);
        $result = $this->connection->delete($key);
        $this->newRelicApi->addCustomMetric(self::METRIC_SESSION_DELETE_TIME, microtime(true) - $startTime);

        return true;
    }

    /**
     * @param int $maxLifetime
     *
     * @return bool
     */
    public function gc($maxLifetime)
    {
        return true;
    }
}
