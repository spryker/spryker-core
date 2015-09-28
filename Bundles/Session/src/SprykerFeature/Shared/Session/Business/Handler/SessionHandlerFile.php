<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Session\Business\Handler;

use Propel\Runtime\Collection\Collection;
use SprykerFeature\Shared\Library\NewRelic\Api;

class SessionHandlerFile implements \SessionHandlerInterface
{

    const METRIC_SESSION_DELETE_TIME = 'File/Session_delete_time';
    const METRIC_SESSION_WRITE_TIME = 'File/Session_write_time';
    const METRIC_SESSION_READ_TIME = 'File/Session_read_time';

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
        $this->savePath = $savePath;
        if (!is_dir($this->savePath)) {
            mkdir($this->savePath, 0777, true);
        }

        return true;
    }

    /**
     * @return bool
     */
    public function close()
    {
        return true;
    }

    /**
     * @param string $sessionId
     *
     * @return null|string
     */
    public function read($sessionId)
    {
        $startTime = microtime(true);
        $key = $this->keyPrefix . $sessionId;
        $sessionFile = $this->savePath . DIRECTORY_SEPARATOR . $key;
        if (!file_exists($sessionFile)) {
            return null;
        }

        $content = file_get_contents($sessionFile);

        Api::getInstance()->addCustomMetric(self::METRIC_SESSION_READ_TIME, microtime(true) - $startTime);

        return $content;
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
        $result = file_put_contents($this->savePath . DIRECTORY_SEPARATOR . $key, $sessionData);
        Api::getInstance()->addCustomMetric(self::METRIC_SESSION_WRITE_TIME, microtime(true) - $startTime);

        return $result > 0;
    }

    /**
     * @param int|string $sessionId
     *
     * @return bool
     */
    public function destroy($sessionId)
    {
        $key = $this->keyPrefix . $sessionId;
        $file = $this->savePath . DIRECTORY_SEPARATOR . $key;
        if (file_exists($file)) {
            $startTime = microtime(true);
            unlink($file);
            Api::getInstance()->addCustomMetric(self::METRIC_SESSION_DELETE_TIME, microtime(true) - $startTime);

            return true;
        }

        return false;
    }

    /**
     * @param int $maxLifetime
     *
     * @return bool
     */
    public function gc($maxLifetime)
    {
        foreach (glob($this->savePath . DIRECTORY_SEPARATOR . $this->keyPrefix . '*') as $file) {
            if (filemtime($file) + $maxLifetime < time() && file_exists($file)) {
                unlink($file);
            }
        }

        return true;
    }

}
