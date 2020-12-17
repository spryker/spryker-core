<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfigurationStorage;

use Codeception\Actor;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageClientInterface;
use Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageDependencyProvider;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Storage\StorageConstants;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductConfigurationStorageClientTester extends Actor
{
    use _generated\ProductConfigurationStorageClientTesterActions;

    /**
     * @uses \Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_PROTOCOL
     */
    protected const REDIS_PROTOCOL = 'STORAGE_REDIS:STORAGE_REDIS_PROTOCOL';

    /**
     *  @uses \Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_SCHEME
     */
    protected const REDIS_SCHEME = 'STORAGE_REDIS:STORAGE_REDIS_SCHEME';

    /**
     * @uses \Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_HOST
     */
    protected const REDIS_HOST = 'STORAGE_REDIS:STORAGE_REDIS_HOST';

    /**
     * @uses \Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_PORT
     */
    protected const REDIS_PORT = 'STORAGE_REDIS:STORAGE_REDIS_PORT';

    /**
     * @uses \Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_DATABASE
     */
    protected const REDIS_DATABASE = 'STORAGE_REDIS:STORAGE_REDIS_DATABASE';

    /**
     * @uses \Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_PASSWORD
     */
    protected const REDIS_PASSWORD = 'STORAGE_REDIS:STORAGE_REDIS_PASSWORD';

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageFactory $productConfigurationStorageFactoryMock
     *
     * @return \Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageClientInterface
     */
    public function getClientMock(MockObject $productConfigurationStorageFactoryMock): ProductConfigurationStorageClientInterface
    {
        $container = new Container();
        $productConfigurationStorageDependencyProvider = new ProductConfigurationStorageDependencyProvider();
        $productConfigurationStorageDependencyProvider->provideServiceLayerDependencies($container);

        $productConfigurationStorageFactoryMock->setContainer($container);

        /** @var \Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageClient $productConfigurationStorageClient */
        $productConfigurationStorageClient = $this->getClient();
        $productConfigurationStorageClient->setFactory($productConfigurationStorageFactoryMock);

        return $productConfigurationStorageClient;
    }

    /**
     * @return \Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageClientInterface
     */
    public function getClient(): ProductConfigurationStorageClientInterface
    {
        return $this->getLocator()->productConfigurationStorage()->client();
    }

    /**
     * @return void
     */
    public function setupStorageRedisConfig(): void
    {
        $this->setConfig(StorageConstants::STORAGE_REDIS_PROTOCOL, Config::hasKey(static::REDIS_SCHEME) ? Config::get(static::REDIS_SCHEME) : Config::get(static::REDIS_PROTOCOL));
        $this->setConfig(StorageConstants::STORAGE_REDIS_PORT, Config::get(static::REDIS_PORT));
        $this->setConfig(StorageConstants::STORAGE_REDIS_HOST, Config::get(static::REDIS_HOST));
        $this->setConfig(StorageConstants::STORAGE_REDIS_DATABASE, Config::get(static::REDIS_DATABASE));
        $this->setConfig(StorageConstants::STORAGE_REDIS_PASSWORD, Config::get(static::REDIS_PASSWORD));
    }
}
