<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionRedis;

use SessionHandlerInterface;
use Spryker\Shared\SessionRedis\Dependency\Client\SessionRedisToRedisClientInterface;
use Spryker\Shared\SessionRedis\Dependency\Service\SessionRedisToMonitoringServiceInterface;
use Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilder;
use Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilderInterface;
use Spryker\Shared\SessionRedis\Handler\LifeTime\SessionRedisLifeTimeCalculator;
use Spryker\Shared\SessionRedis\Handler\LifeTime\SessionRedisLifeTimeCalculatorInterface;
use Spryker\Shared\SessionRedis\Handler\SessionAccountHandlerRedisInterface;
use Spryker\Shared\SessionRedis\Handler\SessionHandlerFactory;
use Spryker\Shared\SessionRedis\Handler\SessionHandlerFactoryInterface;
use Spryker\Shared\SessionRedis\Hasher\BcryptHasher;
use Spryker\Shared\SessionRedis\Hasher\HasherInterface;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapper;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface;
use Spryker\Shared\SessionRedis\Saver\SessionEntitySaver;
use Spryker\Shared\SessionRedis\Saver\SessionEntitySaverInterface;
use Spryker\Shared\SessionRedis\Validator\SessionEntityValidator;
use Spryker\Shared\SessionRedis\Validator\SessionEntityValidatorInterface;
use Spryker\Yves\Kernel\AbstractFactory;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @method \Spryker\Yves\SessionRedis\SessionRedisConfig getConfig()
 */
class SessionRedisFactory extends AbstractFactory
{
    /**
     * @return \SessionHandlerInterface
     */
    public function createSessionRedisHandler(): SessionHandlerInterface
    {
        return $this->createSessionHandlerFactory()->createSessionRedisHandler(
            $this->createSessionRedisWrapper(),
        );
    }

    /**
     * @return \Spryker\Shared\SessionRedis\Handler\SessionAccountHandlerRedisInterface
     */
    public function createSessionCustomerRedisHandler(): SessionAccountHandlerRedisInterface
    {
        return $this->createSessionHandlerFactory()->createSessionCustomerRedisHandler(
            $this->createSessionRedisWrapper(),
        );
    }

    /**
     * @return \Spryker\Shared\SessionRedis\Saver\SessionEntitySaverInterface
     */
    public function createSessionEntitySaver(): SessionEntitySaverInterface
    {
        return new SessionEntitySaver(
            $this->createSessionRedisWrapper(),
            $this->createSessionRedisLifeTimeCalculator(),
            $this->createBcryptHasher(),
            $this->createSessionKeyBuilder(),
        );
    }

    /**
     * @return \Spryker\Shared\SessionRedis\Validator\SessionEntityValidatorInterface
     */
    public function createSessionEntityValidator(): SessionEntityValidatorInterface
    {
        return new SessionEntityValidator(
            $this->createSessionRedisWrapper(),
            $this->createBcryptHasher(),
            $this->createSessionKeyBuilder(),
        );
    }

    /**
     * @return \Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilderInterface
     */
    public function createSessionKeyBuilder(): SessionKeyBuilderInterface
    {
        return new SessionKeyBuilder();
    }

    /**
     * @return \Spryker\Shared\SessionRedis\Hasher\HasherInterface
     */
    public function createBcryptHasher(): HasherInterface
    {
        return new BcryptHasher();
    }

    /**
     * @return \SessionHandlerInterface
     */
    public function createSessionHandlerRedisLocking(): SessionHandlerInterface
    {
        return $this->createSessionHandlerFactory()->createSessionHandlerRedisLocking(
            $this->createSessionRedisWrapper(),
        );
    }

    /**
     * @return \Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface
     */
    public function createSessionRedisWrapper(): SessionRedisWrapperInterface
    {
        return new SessionRedisWrapper(
            $this->getRedisClient(),
            SessionRedisConfig::SESSION_REDIS_CONNECTION_KEY,
            $this->getConfig()->getRedisConnectionConfiguration(),
        );
    }

    /**
     * @return \Spryker\Shared\SessionRedis\Handler\SessionHandlerFactoryInterface
     */
    public function createSessionHandlerFactory(): SessionHandlerFactoryInterface
    {
        return new SessionHandlerFactory(
            $this->getMonitoringService(),
            $this->createSessionRedisLifeTimeCalculator(),
            $this->getConfig()->getLockingTimeoutMilliseconds(),
            $this->getConfig()->getLockingRetryDelayMicroseconds(),
            $this->getConfig()->getLockingLockTtlMilliseconds(),
        );
    }

    /**
     * @return \Spryker\Shared\SessionRedis\Handler\LifeTime\SessionRedisLifeTimeCalculatorInterface
     */
    public function createSessionRedisLifeTimeCalculator(): SessionRedisLifeTimeCalculatorInterface
    {
        return new SessionRedisLifeTimeCalculator(
            $this->getRequestStack(),
            $this->getSessionRedisLifeTimeCalculatorPlugins(),
            $this->getConfig()->getSessionLifetime(),
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    public function getRequestStack(): RequestStack
    {
        return $this->getProvidedDependency(SessionRedisDependencyProvider::REQUEST_STACK);
    }

    /**
     * @return \Spryker\Shared\SessionRedis\Dependency\Service\SessionRedisToMonitoringServiceInterface
     */
    public function getMonitoringService(): SessionRedisToMonitoringServiceInterface
    {
        return $this->getProvidedDependency(SessionRedisDependencyProvider::SERVICE_MONITORING);
    }

    /**
     * @return \Spryker\Shared\SessionRedis\Dependency\Client\SessionRedisToRedisClientInterface
     */
    public function getRedisClient(): SessionRedisToRedisClientInterface
    {
        return $this->getProvidedDependency(SessionRedisDependencyProvider::CLIENT_REDIS);
    }

    /**
     * @return array<\Spryker\Shared\SessionRedisExtension\Dependency\Plugin\SessionRedisLifeTimeCalculatorPluginInterface>
     */
    public function getSessionRedisLifeTimeCalculatorPlugins(): array
    {
        return $this->getProvidedDependency(SessionRedisDependencyProvider::PLUGINS_SESSION_REDIS_LIFE_TIME_CALCULATOR);
    }
}
