<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage;

use Predis\Client;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Storage\Redis\Service;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Session\SessionConstants;
use Spryker\Shared\Storage\StorageConstants;

class StorageFactory extends AbstractFactory
{

    const DEFAULT_REDIS_DATABASE = 0;

    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    protected static $storageService;

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    public function createService()
    {
        return new Service(
            $this->createClient()
        );
    }

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    public function createCachedService()
    {
        if (static::$storageService === null) {
            static::$storageService = $this->createService();
        }

        return static::$storageService;
    }

    /**
     * @return \Predis\ClientInterface
     */
    protected function createClient()
    {
        return new Client($this->getConfig());
    }

    /**
     * @return array
     */
    protected function getConfig()
    {
        return $this->getConnectionParameters();
    }

    /**
     * @return array
     */
    protected function getConnectionParameters()
    {
        // TODO: Remove defaults. The default Config::get() values are for BC only.
        $config = [
            'protocol' => Config::get(StorageConstants::STORAGE_REDIS_PROTOCOL, Config::get(SessionConstants::YVES_SESSION_REDIS_PROTOCOL)),
            'port' => Config::get(StorageConstants::STORAGE_REDIS_PORT, Config::get(SessionConstants::YVES_SESSION_REDIS_PORT)),
            'host' => Config::get(StorageConstants::STORAGE_REDIS_HOST, Config::get(SessionConstants::YVES_SESSION_REDIS_HOST)),
            'database' => Config::get(StorageConstants::STORAGE_REDIS_DATABASE, static::DEFAULT_REDIS_DATABASE),
        ];

        // TODO: Remove elseif. Only for BC.
        if (Config::hasKey(StorageConstants::STORAGE_REDIS_PASSWORD)) {
            $config['password'] = Config::get(StorageConstants::STORAGE_REDIS_PASSWORD);
        } elseif (Config::hasKey(SessionConstants::YVES_SESSION_REDIS_PASSWORD)) {
            $config['password'] = Config::get(SessionConstants::YVES_SESSION_REDIS_PASSWORD);
        }

        // TODO: Remove elseif. Only for BC.
        $config['persistent'] = false;
        if (Config::hasKey(StorageConstants::STORAGE_PERSISTENT_CONNECTION)) {
            $config['persistent'] = (bool)Config::get(StorageConstants::STORAGE_PERSISTENT_CONNECTION);
        } elseif (Config::hasKey(SessionConstants::YVES_SESSION_PERSISTENT_CONNECTION)) {
            $config['persistent'] = (bool)Config::get(SessionConstants::YVES_SESSION_PERSISTENT_CONNECTION);
        }

        return $config;
    }

}
