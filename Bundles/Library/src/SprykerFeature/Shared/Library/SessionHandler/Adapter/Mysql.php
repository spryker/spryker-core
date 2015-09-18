<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\SessionHandler\Adapter;

use PDO as PDO;

class Mysql implements \SessionHandlerInterface
{

    /**
     * @var PDO
     */
    protected $connection = null;

    /**
     * @var string
     */
    protected $host;

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
    protected $keyPrefix = 'session:';

    /**
     * Define a default session lifetime time of 10 minutes.
     */
    protected $lifetime = 600;

    /**
     * @var int
     */
    protected $port = 3306;

    /**
     * @param array $hosts
     * @param null|string $user
     * @param null|string  $password
     * @param int $lifetime
     */
    public function __construct($hosts = ['127.0.0.1:3306'], $user = null, $password = null, $lifetime = 600)
    {
        $host = $hosts[0];
        if (strpos($host, ':')) {
            $parts = explode(':', $host);
            $host = $parts[0];
            $this->port = $parts[1];
        }

        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->lifetime = $lifetime;

        $databaseName = 'shared_data';
        $dsn = 'mysql:host=' . $this->host . ';port=' . $this->port . ';dbname=' . $databaseName;
        $this->connection = new PDO($dsn, $this->user, $this->password);

        $this->initDb();
    }

    /**
     * @param string $savePath
     * @param string $sessionName
     *
     * @return bool
     */
    public function open($savePath, $sessionName)
    {
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

        $store = \SprykerEngine\Shared\Kernel\Store::getInstance()->getStoreName();
        $environment = \SprykerFeature_Shared_Library_Environment::getInstance()->getEnvironment();
        $query = 'SELECT * FROM session WHERE session.key=? AND session.store=? AND session.environment=? AND session.expires >= session.updated_at + ' . $this->lifetime . ' LIMIT 1';

        $statement = $this->connection->prepare($query);
        $statement->execute([$key, $store, $environment]);
        $result = $statement->fetch();
        \SprykerFeature\Shared\Library\NewRelic\Api::getInstance()->addCustomMetric('Mysql/Session_read_time', microtime(true) - $startTime);

        return $result ? json_decode($result['value'], true) : null;
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
        $environment = \SprykerFeature_Shared_Library_Environment::getInstance()->getEnvironment();
        $data = json_encode($sessionData);
        $expireTimestamp = time() + $this->lifetime;
        $expires = date('Y-m-d H:i:s', $expireTimestamp);

        $storeName = \SprykerEngine\Shared\Kernel\Store::getInstance()->getStoreName();
        $timestamp = date('Y-m-d H:i:s', time());
        $query = 'REPLACE INTO session (session.key, session.value, session.store, session.environment, session.expires, session.updated_at) VALUES (?,?,?,?,?,?)';

        $statement = $this->connection->prepare($query);
        $result = $statement->execute([$key, $data, $storeName, $environment, $expires, $timestamp]);

        \SprykerFeature\Shared\Library\NewRelic\Api::getInstance()->addCustomMetric('Mysql/Session_write_time', microtime(true) - $startTime);

        return $result;
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
        \SprykerFeature\Shared\Library\NewRelic\Api::getInstance()->addCustomMetric('Couchbase/Session_delete_time', microtime(true) - $startTime);

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

    protected function initDb()
    {
        $query = "CREATE TABLE IF NOT EXISTS `session` (
          `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `key` varchar(255) NOT NULL DEFAULT '',
          `value` longtext NOT NULL,
          `store` varchar(2) NOT NULL DEFAULT '',
          `environment` enum('DEVELOPMENT','TESTING','STAGING','PRODUCTION','QUALITY01','QUALITY02','QUALITY03','QUALITY04') NOT NULL DEFAULT 'DEVELOPMENT',
          `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
          PRIMARY KEY (`id`),
          UNIQUE KEY `key` (`key`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";

        $statement = $this->connection->query($query);
        $statement->execute();
    }

}
