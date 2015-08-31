<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\SessionHandler\Adapter;

use Predis\Client;

class Redis implements \SessionHandlerInterface
{

    /**
     * @var Client
     */
    protected $connection = null;

    /**
     * @var string
     */
    protected $keyPrefix = 'session:';

    /**
     * Define a default session lifetime time of 10 minutes.
     */
    protected $lifetime = 600;

    /**
     * @var string
     */
    protected $savePath;

    /**
     * @param string $savePath
     * @param integer $lifetime
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
    public function close() {
        unset($this->connection);

        return true;
    }

    /**
     * @param string $sessionId
     *
     * @return null|string
     */
    public function read($sessionId) {
        $key = $this->keyPrefix . $sessionId;
        $startTime = microtime(true);
        $result = $this->connection->get($key);
        \SprykerFeature_Shared_Library_NewRelic_Api::getInstance()->addCustomMetric('Redis/Session_read_time', microtime(true) - $startTime);

        return $result ? json_decode($result, true) : null;
    }

    /**
     * @param string $sessionId
     * @param string $sessionData
     *
     * @return bool
     */
    public function write($sessionId, $sessionData) {
        $key = $this->keyPrefix . $sessionId;

        if (empty($sessionData)) {
            return false;
        }

        $startTime = microtime(true);
        $result = $this->connection->setex($key, $this->lifetime, json_encode($sessionData));
        \SprykerFeature_Shared_Library_NewRelic_Api::getInstance()->addCustomMetric('Redis/Session_write_time', microtime(true) - $startTime);

        return $result ? true : false;
    }

    /**
     * @param int|string $sessionId
     *
     * @return bool
     */
    public function destroy($sessionId) {
        $key = $this->keyPrefix . $sessionId;

        $startTime = microtime(true);
        $result = $this->connection->del($key);
        \SprykerFeature_Shared_Library_NewRelic_Api::getInstance()->addCustomMetric('Redis/Session_delete_time', microtime(true) - $startTime);

        return $result ? true : false;
    }

    /**
     * @param int $maxLifetime
     *
     * @return bool
     */
    public function gc($maxLifetime) {
        return true;
    }

}
