<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase\ConnectionProvider;

use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Connection\ConnectionManagerSingle;
use Propel\Runtime\Propel;
use Spryker\Client\StorageDatabase\StorageDatabaseConfig;
use Throwable;

class ConnectionProvider implements ConnectionProviderInterface
{
    protected const CONNECTION_NAME = 'storage connection';

    /**
     * @var \Propel\Runtime\Connection\ConnectionInterface|null
     */
    protected static $connection;

    /**
     * @var \Spryker\Client\StorageDatabase\StorageDatabaseConfig
     */
    private $config;

    /**
     * @param \Spryker\Client\StorageDatabase\StorageDatabaseConfig $config
     */
    public function __construct(StorageDatabaseConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return \Propel\Runtime\Connection\ConnectionInterface
     */
    public function getConnection(): ConnectionInterface
    {
        if (!static::$connection) {
            $this->establishConnection();
        }

        return static::$connection;
    }

    /**
     * @return void
     */
    protected function establishConnection(): void
    {
        $manager = new ConnectionManagerSingle();
        $manager->setConfiguration($this->getPropelConfig());
        $manager->setName(static::CONNECTION_NAME);

        $serviceContainer = $this->getServiceContainer();
        $serviceContainer->setAdapterClass(static::CONNECTION_NAME, $this->config->getDatabaseEngine());
        $serviceContainer->setConnectionManager(static::CONNECTION_NAME, $manager);
        $serviceContainer->setDefaultDatasource(static::CONNECTION_NAME);

        $this->setupConnection();
    }

    /**
     * @return \Propel\Runtime\ServiceContainer\StandardServiceContainer
     */
    protected function getServiceContainer()
    {
        /** @var \Propel\Runtime\ServiceContainer\StandardServiceContainer $serviceContainer */
        $serviceContainer = Propel::getServiceContainer();

        return $serviceContainer;
    }

    /**
     * Allowed try/catch. If we have no database setup, getConnection throws an Exception
     * ServiceProvider is called more then once and after setup of database we can enable debug
     *
     * @return bool
     */
    private function setupConnection()
    {
        try {
            Propel::getConnection();

            return true;
        } catch (Throwable $e) {
            // throw new connection failed exception
        }
    }

    /**
     * @return array
     */
    private function getPropelConfig(): array
    {
        return $this->config->getConnectionConfig();
    }
}
