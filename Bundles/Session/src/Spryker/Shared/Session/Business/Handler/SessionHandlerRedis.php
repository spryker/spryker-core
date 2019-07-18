<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Session\Business\Handler;

use Predis\Client;
use SessionHandlerInterface;
use Spryker\Shared\Session\Dependency\Service\SessionToMonitoringServiceInterface;

/**
 * @deprecated Use `Spryker\Shared\SessionRedis\Handler\SessionHandlerRedis` instead.
 */
class SessionHandlerRedis implements SessionHandlerInterface
{
    public const METRIC_SESSION_DELETE_TIME = 'Redis/Session_delete_time';
    public const METRIC_SESSION_WRITE_TIME = 'Redis/Session_write_time';
    public const METRIC_SESSION_READ_TIME = 'Redis/Session_read_time';

    /**
     * @var \Predis\Client|null
     */
    protected $connection;

    /**
     * @var string
     */
    protected $keyPrefix = 'session:';

    /**
     * @var array|string
     */
    protected $connectionParameters;

    /**
     * @var int
     */
    protected $lifetime;

    /**
     * @var \Spryker\Shared\Session\Dependency\Service\SessionToMonitoringServiceInterface
     */
    protected $monitoringService;

    /**
     * @var array
     */
    private $connectionOptions;

    /**
     * @param array|string $connectionParameters
     * @param int $lifetime
     * @param \Spryker\Shared\Session\Dependency\Service\SessionToMonitoringServiceInterface $monitoringService
     * @param array $connectionOptions
     */
    public function __construct($connectionParameters, $lifetime, SessionToMonitoringServiceInterface $monitoringService, array $connectionOptions = [])
    {
        $this->connectionParameters = $connectionParameters;
        $this->lifetime = $lifetime;
        $this->monitoringService = $monitoringService;
        $this->connectionOptions = $connectionOptions;
    }

    /**
     * @param string $savePath
     * @param string $sessionName
     *
     * @return bool
     */
    public function open($savePath, $sessionName)
    {
        $this->connection = new Client($this->connectionParameters, $this->connectionOptions);

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
        $result = $this->connection->get($key);
        $this->monitoringService->addCustomParameter(self::METRIC_SESSION_READ_TIME, microtime(true) - $startTime);

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
            return true;
        }

        $startTime = microtime(true);
        $result = $this->connection->setex($key, $this->lifetime, json_encode($sessionData));
        $this->monitoringService->addCustomParameter(self::METRIC_SESSION_WRITE_TIME, microtime(true) - $startTime);

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
        $this->connection->del($key);
        $this->monitoringService->addCustomParameter(self::METRIC_SESSION_DELETE_TIME, microtime(true) - $startTime);

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
