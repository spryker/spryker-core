<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Session\Business\Handler;

use Predis\Client;
use SprykerFeature\Shared\NewRelic\Api;

class SessionHandlerRedis implements \SessionHandlerInterface
{

    const METRIC_SESSION_DELETE_TIME = 'Redis/Session_delete_time';
    const METRIC_SESSION_WRITE_TIME = 'Redis/Session_write_time';
    const METRIC_SESSION_READ_TIME = 'Redis/Session_read_time';

    /**
     * @var Client
     */
    protected $connection = null;

    /**
     * @var string
     */
    protected $keyPrefix = 'session:';

    /**
     * @var int
     */
    protected $lifetime;

    /**
     * @var string
     */
    protected $savePath;

    /**
     * @param string $savePath
     * @param int $lifetime
     */
    public function __construct($savePath, $lifetime)
    {
        $this->savePath = $savePath;
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
        $this->connection = new Client($this->savePath);

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
     * @return null|string
     */
    public function read($sessionId)
    {
        $key = $this->keyPrefix . $sessionId;
        $startTime = microtime(true);
        $result = $this->connection->get($key);
        Api::getInstance()->addCustomMetric(self::METRIC_SESSION_READ_TIME, microtime(true) - $startTime);

        return $result ? json_decode($result, true) : null;
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

        if (empty($sessionData)) {
            return false;
        }

        $startTime = microtime(true);
        $result = $this->connection->setex($key, $this->lifetime, json_encode($sessionData));
        Api::getInstance()->addCustomMetric(self::METRIC_SESSION_WRITE_TIME, microtime(true) - $startTime);

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
        $result = $this->connection->del($key);
        Api::getInstance()->addCustomMetric(self::METRIC_SESSION_DELETE_TIME, microtime(true) - $startTime);

        return $result ? true : false;
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
