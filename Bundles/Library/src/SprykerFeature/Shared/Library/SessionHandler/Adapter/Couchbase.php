<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\SessionHandler\Adapter;

class Couchbase implements \SessionHandlerInterface
{

    /**
     * @var \Couchbase
     */
    protected $connection = null;

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
     * @var null|string
     */
    protected $bucketName = null;

    /**
     * @var bool
     */
    protected $persistent;

    /**
     * @var string
     */
    protected $keyPrefix = 'session:';

    /**
     * Define a default session lifetime time of 10 minutes.
     */
    protected $lifetime = 600;

    /**
     * @param array $hosts
     * @param null|string $user
     * @param null|string  $password
     * @param string $bucketName
     * @param bool $persistent
     * @param int $lifetime
     */
    public function __construct($hosts = ['127.0.0.1:8091'], $user = null, $password = null,  $bucketName = 'default', $persistent = true, $lifetime = 600) {
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
    public function open($savePath, $sessionName) {
        $this->connection = new \Couchbase($this->hosts, $this->user, $this->password, $this->bucketName, $this->persistent);

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
        $result = $this->connection->getAndTouch($key, $this->lifetime);
        \SprykerFeature_Shared_Library_NewRelic_Api::getInstance()->addCustomMetric('Couchbase/Session_read_time', microtime(true) - $startTime);

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
        $result = $this->connection->set($key, json_encode($sessionData), $this->lifetime);
        \SprykerFeature_Shared_Library_NewRelic_Api::getInstance()->addCustomMetric('Couchbase/Session_write_time', microtime(true) - $startTime);

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
        $result = $this->connection->delete($key);
        \SprykerFeature_Shared_Library_NewRelic_Api::getInstance()->addCustomMetric('Couchbase/Session_delete_time', microtime(true) - $startTime);

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
