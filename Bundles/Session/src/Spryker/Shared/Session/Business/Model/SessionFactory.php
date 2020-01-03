<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Session\Business\Model;

use Predis\Client;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Session\Business\Handler\KeyGenerator\Redis\RedisLockKeyGenerator;
use Spryker\Shared\Session\Business\Handler\KeyGenerator\Redis\RedisSessionKeyGenerator;
use Spryker\Shared\Session\Business\Handler\Lock\Redis\RedisSpinLockLocker;
use Spryker\Shared\Session\Business\Handler\SessionHandlerCouchbase;
use Spryker\Shared\Session\Business\Handler\SessionHandlerFile;
use Spryker\Shared\Session\Business\Handler\SessionHandlerMysql;
use Spryker\Shared\Session\Business\Handler\SessionHandlerRedis;
use Spryker\Shared\Session\Business\Handler\SessionHandlerRedisLocking;
use Spryker\Shared\Session\Dependency\Service\SessionToMonitoringServiceInterface;
use Spryker\Shared\Session\SessionConstants;

abstract class SessionFactory
{
    public const BUCKET_NAME_POSTFIX = 'sessions';
    public const PASSWORD = 'password';
    public const USER = 'user';

    /**
     * @param string $savePath e.g. '10.10.10.1:8091;10.10.10.2:8091'
     *
     * @return \Spryker\Shared\Session\Business\Handler\SessionHandlerCouchbase
     */
    public function registerCouchbaseSessionHandler($savePath)
    {
        // get the credentials from the first defined couchbase host
        $credentials = $this->getCredentialsFromSavePathSegment(explode(';', $savePath)[0]);
        $user = !empty($credentials) && array_key_exists(self::USER, $credentials) ? $credentials[self::USER] : null;
        $password = !empty($credentials) && array_key_exists(self::PASSWORD, $credentials) ? $credentials[self::PASSWORD] : null;
        $hosts = $this->getHostsFromSavePath($savePath);
        $lifetime = $this->getSessionLifetime();

        $handler = new SessionHandlerCouchbase($this->getMonitoringService(), $hosts, $user, $password, $this->getBucketName(), true, $lifetime);
        $this->setSessionSaveHandler($handler);

        return $handler;
    }

    /**
     * @param string $savePath e.g. '10.10.10.1:3306;10.10.10.2:3306'
     *
     * @return \Spryker\Shared\Session\Business\Handler\SessionHandlerMysql
     */
    public function registerMysqlSessionHandler($savePath)
    {
        $credentials = $this->getCredentialsFromSavePathSegment(explode(';', $savePath)[0]);
        $user = !empty($credentials) && array_key_exists(self::USER, $credentials) ? $credentials[self::USER] : null;
        $password = !empty($credentials) && array_key_exists(self::PASSWORD, $credentials) ? $credentials[self::PASSWORD] : null;
        $hosts = $this->getHostsFromSavePath($savePath);
        $lifetime = $this->getSessionLifetime();

        $handler = new SessionHandlerMysql($this->getMonitoringService(), $hosts, $user, $password, $lifetime);
        $this->setSessionSaveHandler($handler);

        return $handler;
    }

    /**
     * @param array|string $connectionParameters
     * @param array $connectionOptions
     *
     * @return \Spryker\Shared\Session\Business\Handler\SessionHandlerRedis
     */
    public function registerRedisSessionHandler($connectionParameters, array $connectionOptions = [])
    {
        $handler = $this->createSessionHandlerRedis($connectionParameters, $connectionOptions);
        $this->setSessionSaveHandler($this->createSessionHandlerRedis($connectionParameters, $connectionOptions));

        return $handler;
    }

    /**
     * @deprecated Use `Spryker\Shared\SessionExtension\Dependency\Plugin\SessionHandlerProviderPluginInterface` instead.
     *
     * @param array|string $connectionParameters
     * @param array $connectionOptions
     *
     * @return \Spryker\Shared\Session\Business\Handler\SessionHandlerRedis
     */
    public function createSessionHandlerRedis($connectionParameters, array $connectionOptions = [])
    {
        $lifetime = $this->getSessionLifetime();

        return new SessionHandlerRedis($connectionParameters, $lifetime, $this->getMonitoringService(), $connectionOptions);
    }

    /**
     * @deprecated Use `Spryker\Shared\SessionExtension\Dependency\Plugin\SessionHandlerProviderPluginInterface` instead.
     *
     * @param array|string $connectionParameters
     * @param array $connectionOptions
     *
     * @return void
     */
    public function registerRedisLockingSessionHandler($connectionParameters, array $connectionOptions = [])
    {
        $this->setSessionSaveHandler($this->createRedisLockingSessionHandler($connectionParameters, $connectionOptions));
    }

    /**
     * @deprecated Use `Spryker\Shared\SessionExtension\Dependency\Plugin\SessionHandlerProviderPluginInterface` instead.
     *
     * @param array|string $connectionParameters
     * @param array $connectionOptions
     *
     * @return \Spryker\Shared\Session\Business\Handler\SessionHandlerRedisLocking
     */
    public function createRedisLockingSessionHandler($connectionParameters, array $connectionOptions = [])
    {
        $redisClient = $this->createRedisClient($connectionParameters, $connectionOptions);

        return new SessionHandlerRedisLocking(
            $redisClient,
            $this->createRedisSpinLockLocker($redisClient),
            $this->createRedisSessionKeyGenerator(),
            $this->getSessionLifetime()
        );
    }

    /**
     * @deprecated Use `Spryker\Shared\SessionExtension\Dependency\Plugin\SessionHandlerProviderPluginInterface` instead.
     *
     * @param array|string $connectionParameters
     * @param array $connectionOptions
     *
     * @return \Predis\Client
     */
    public function createRedisClient($connectionParameters, array $connectionOptions = [])
    {
        return new Client($connectionParameters, $connectionOptions);
    }

    /**
     * @deprecated Use `Spryker\Shared\SessionExtension\Dependency\Plugin\SessionHandlerProviderPluginInterface` instead.
     *
     * @param \Predis\Client $redisClient
     *
     * @return \Spryker\Shared\Session\Business\Handler\Lock\SessionLockerInterface
     */
    public function createRedisSpinLockLocker(Client $redisClient)
    {
        return new RedisSpinLockLocker(
            $redisClient,
            $this->createRedisLockKeyGenerator(),
            Config::get(SessionConstants::SESSION_HANDLER_REDIS_LOCKING_TIMEOUT_MILLISECONDS, 0),
            Config::get(SessionConstants::SESSION_HANDLER_REDIS_LOCKING_RETRY_DELAY_MICROSECONDS, 0),
            Config::get(SessionConstants::SESSION_HANDLER_REDIS_LOCKING_LOCK_TTL_MILLISECONDS, 0)
        );
    }

    /**
     * @deprecated Use `Spryker\Shared\SessionExtension\Dependency\Plugin\SessionHandlerProviderPluginInterface` instead.
     *
     * @return \Spryker\Shared\Session\Business\Handler\KeyGenerator\LockKeyGeneratorInterface
     */
    public function createRedisLockKeyGenerator()
    {
        return new RedisLockKeyGenerator(
            $this->createRedisSessionKeyGenerator()
        );
    }

    /**
     * @deprecated Use `Spryker\Shared\SessionExtension\Dependency\Plugin\SessionHandlerProviderPluginInterface` instead.
     *
     * @return \Spryker\Shared\Session\Business\Handler\KeyGenerator\SessionKeyGeneratorInterface
     */
    protected function createRedisSessionKeyGenerator()
    {
        return new RedisSessionKeyGenerator();
    }

    /**
     * @param string $savePath
     *
     * @return \Spryker\Shared\Session\Business\Handler\SessionHandlerFile
     */
    public function registerFileSessionHandler($savePath)
    {
        $handler = $this->createSessionHandlerFile($savePath);
        $this->setSessionSaveHandler($handler);

        return $handler;
    }

    /**
     * @deprecated Use `Spryker\Shared\SessionExtension\Dependency\Plugin\SessionHandlerProviderPluginInterface` instead.
     *
     * @param string $savePath
     *
     * @return \Spryker\Shared\Session\Business\Handler\SessionHandlerFile
     */
    public function createSessionHandlerFile($savePath)
    {
        $lifetime = $this->getSessionLifetime();

        return new SessionHandlerFile($savePath, $lifetime, $this->getMonitoringService());
    }

    /**
     * @return int
     */
    abstract protected function getSessionLifetime();

    /**
     * @return \Spryker\Shared\Session\Dependency\Service\SessionToMonitoringServiceInterface
     */
    abstract public function getMonitoringService(): SessionToMonitoringServiceInterface;

    /**
     * @param \SessionHandlerInterface $handler
     *
     * @return void
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
        $environment = $this->getEnvironmentName();

        return $storeName . '_' . $environment . '_' . self::BUCKET_NAME_POSTFIX;
    }

    /**
     * @param string $savePathSegment
     *
     * @return array
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
        }

        return [];
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

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return string
     */
    protected function getEnvironmentName(): string
    {
        return APPLICATION_ENV;
    }
}
