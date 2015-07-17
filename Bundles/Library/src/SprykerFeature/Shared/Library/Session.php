<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library;

use SprykerFeature\Shared\Library\SessionHandler\Adapter\Couchbase;
use SprykerFeature\Shared\Library\SessionHandler\Adapter\Mysql;
use SprykerFeature\Shared\Library\SessionHandler\Adapter\Redis;

class Session
{

    const BUCKET_NAME_POSTFIX = 'sessions';
    const PASSWORD = 'password';
    const USER = 'user';

    /**
     * @param string $savePath e.g. '10.10.10.1:8091;10.10.10.2:8091'
     *
     * @return \SprykerFeature\Shared\Library\SessionHandler\Adapter\Couchbase
     */
    public static function registerCouchbaseSessionHandler($savePath)
    {
        // get the credentials from the first defined couchbase host
        $credentials = self::getCredentialsFromSavePathSegment(explode(';', $savePath)[0]);
        $user = is_array($credentials) && array_key_exists(self::USER, $credentials) ? $credentials[self::USER] : null;
        $password = is_array($credentials) && array_key_exists(self::PASSWORD, $credentials) ? $credentials[self::PASSWORD] : null;
        $hosts = self::getHostsFromSavePath($savePath);
        $lifetime = ini_get('session.gc_maxlifetime');

        $handler = new Couchbase($hosts, $user, $password, self::getBucketName(), true, $lifetime);
        session_set_save_handler(
            [$handler, 'open'],
            [$handler, 'close'],
            [$handler, 'read'],
            [$handler, 'write'],
            [$handler, 'destroy'],
            [$handler, 'gc']
        );

        return $handler;
    }

    /**
     * @param string $savePath e.g. '10.10.10.1:3306;10.10.10.2:3306'
     *
     * @return Mysql
     */
    public static function registerMysqlSessionHandler($savePath)
    {
        $credentials = self::getCredentialsFromSavePathSegment(explode(';', $savePath)[0]);
        $user = is_array($credentials) && array_key_exists(self::USER, $credentials) ? $credentials[self::USER] : null;
        $password = is_array($credentials) && array_key_exists(self::PASSWORD, $credentials) ? $credentials[self::PASSWORD] : null;
        $hosts = self::getHostsFromSavePath($savePath);
        $lifetime = ini_get('session.gc_maxlifetime');

        $handler = new Mysql($hosts, $user, $password, $lifetime);
        session_set_save_handler(
            [$handler, 'open'],
            [$handler, 'close'],
            [$handler, 'read'],
            [$handler, 'write'],
            [$handler, 'destroy'],
            [$handler, 'gc']
        );

        return $handler;
    }

    /**
     * @param string $savePath
     *
     * @return Redis
     */
    public static function registerRedisSessionHandler($savePath)
    {
        $handler = new Redis($savePath);
        session_set_save_handler(
            [$handler, 'open'],
            [$handler, 'close'],
            [$handler, 'read'],
            [$handler, 'write'],
            [$handler, 'destroy'],
            [$handler, 'gc']
        );

        return $handler;
    }

    /**
     * @return string
     */
    protected static function getBucketName()
    {
        $storeName = \SprykerEngine\Shared\Kernel\Store::getInstance()->getStoreName();
        $environment = \SprykerFeature_Shared_Library_Environment::getInstance()->getEnvironment();

        return $storeName . '_' . $environment . '_' . self::BUCKET_NAME_POSTFIX;
    }

    /**
     * @param string $savePathSegment
     *
     * @return array|null
     */
    protected static function getCredentialsFromSavePathSegment($savePathSegment)
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
            return;
        }
    }

    /**
     * @param string $savePath
     *
     * @return array
     */
    protected static function getHostsFromSavePath($savePath)
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
