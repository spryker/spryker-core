<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Session\Business\Model;

use SprykerEngine\Shared\Kernel\Store;
use SprykerFeature\Shared\Session\Business\Handler\SessionHandlerCouchbase;
use SprykerFeature\Shared\Session\Business\Handler\SessionHandlerFile;
use SprykerFeature\Shared\Session\Business\Handler\SessionHandlerMysql;
use SprykerFeature\Shared\Session\Business\Handler\SessionHandlerRedis;

abstract class SessionFactory
{

    const BUCKET_NAME_POSTFIX = 'sessions';
    const PASSWORD = 'password';
    const USER = 'user';

    /**
     * @param string $savePath e.g. '10.10.10.1:8091;10.10.10.2:8091'
     *
     * @return SessionHandlerCouchbase
     */
    public function registerCouchbaseSessionHandler($savePath)
    {
        // get the credentials from the first defined couchbase host
        $credentials = $this->getCredentialsFromSavePathSegment(explode(';', $savePath)[0]);
        $user = is_array($credentials) && array_key_exists(self::USER, $credentials) ? $credentials[self::USER] : null;
        $password = is_array($credentials) && array_key_exists(self::PASSWORD, $credentials) ? $credentials[self::PASSWORD] : null;
        $hosts = $this->getHostsFromSavePath($savePath);
        $lifetime = $this->getSessionLifetime();

        $handler = new SessionHandlerCouchbase($hosts, $user, $password, $this->getBucketName(), true, $lifetime);
        $this->setSessionSaveHandler($handler);

        return $handler;
    }

    /**
     * @param string $savePath e.g. '10.10.10.1:3306;10.10.10.2:3306'
     *
     * @return SessionHandlerMysql
     */
    public function registerMysqlSessionHandler($savePath)
    {
        $credentials = $this->getCredentialsFromSavePathSegment(explode(';', $savePath)[0]);
        $user = is_array($credentials) && array_key_exists(self::USER, $credentials) ? $credentials[self::USER] : null;
        $password = is_array($credentials) && array_key_exists(self::PASSWORD, $credentials) ? $credentials[self::PASSWORD] : null;
        $hosts = $this->getHostsFromSavePath($savePath);
        $lifetime = $this->getSessionLifetime();

        $handler = new SessionHandlerMysql($hosts, $user, $password, $lifetime);
        $this->setSessionSaveHandler($handler);

        return $handler;
    }

    /**
     * @param string $savePath
     *
     * @return SessionHandlerRedis
     */
    public function registerRedisSessionHandler($savePath)
    {
        $lifetime = $this->getSessionLifetime();
        $handler = new SessionHandlerRedis($savePath, $lifetime);
        $this->setSessionSaveHandler($handler);

        return $handler;
    }

    /**
     * @param string $savePath
     *
     * @return SessionHandlerFile
     */
    public function registerFileSessionHandler($savePath)
    {
        $lifetime = $this->getSessionLifetime();
        $handler = new SessionHandlerFile($savePath, $lifetime);
        $this->setSessionSaveHandler($handler);

        return $handler;
    }

    /**
     * @return int
     */
    abstract protected function getSessionLifetime();

    /**
     * @param $handler
     */
    protected function setSessionSaveHandler($handler)
    {
        session_set_save_handler(
            [$handler, 'open'],
            [$handler, 'close'],
            [$handler, 'read'],
            [$handler, 'write'],
            [$handler, 'destroy'],
            [$handler, 'gc']
        );
    }

    /**
     * @return string
     */
    protected function getBucketName()
    {
        $storeName = Store::getInstance()->getStoreName();
        $environment = \SprykerFeature\Shared\Library\Environment::getInstance()->getEnvironment();

        return $storeName . '_' . $environment . '_' . self::BUCKET_NAME_POSTFIX;
    }

    /**
     * @param string $savePathSegment
     *
     * @return array|null
     */
    protected function getCredentialsFromSavePathSegment($savePathSegment)
    {
        if (strstr($savePathSegment, '@')) {
            $credentials = explode('@', $savePathSegment)[0];

            if (strstr($credentials, ':')) {
                $parts = explode(':', $credentials);

                return [
                    self::USER => $parts[0],
                    self::PASSWORD => $parts[1],
                ];
            } else {
                return [self::USER => $credentials];
            }
        } else {
            return null;
        }
    }

    /**
     * @param string $savePath
     *
     * @return array
     */
    protected function getHostsFromSavePath($savePath)
    {
        $hosts = [];
        $hostsInfo = explode(';', $savePath);

        foreach ($hostsInfo as $hostInfo) {
            if (strstr($hostInfo, '@')) {
                $hosts[] = explode('@', $hostInfo)[1];
            } else {
                $hosts[] = $hostInfo;
            }
        }

        return $hosts;
    }

}
