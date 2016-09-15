<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Storage\Provider;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\AbstractClientProvider;
use Spryker\Shared\Storage\StorageConstants;

/**
 * @deprecated Not used anymore.
 */
abstract class AbstractKvProvider extends AbstractClientProvider
{

    /**
     * @deprecated Not used.
     */
    const METHOD_PREFIX = 'createClient';

    const KV_ADAPTER_REDIS = 'redis';

    /**
     * Default Redis database number
     *
     * @const int
     */
    const DEFAULT_REDIS_DATABASE = 0;

    /**
     * @var string
     */
    protected $clientType;

    /**
     * @return mixed
     */
    protected function createZedClient()
    {
        //TODO: Remove BC default.
        $kvName = Config::get(StorageConstants::STORAGE_KV_SOURCE, Config::get(ApplicationConstants::STORAGE_KV_SOURCE));
        $kvConfig = $this->getConfigByKvName($kvName);
        $methodName = $this->createMethodName($kvName);

        return new $methodName($kvConfig);
    }

    /**
     * @param string $kvName
     *
     * @return string
     */
    protected function createMethodName($kvName)
    {
        return ucfirst($kvName) . $this->clientType;
    }

    /**
     * @param string $kvName
     *
     * @throws \ErrorException
     *
     * @return array
     */
    public function getConfigByKvName($kvName)
    {
        switch ($kvName) {
            case self::KV_ADAPTER_REDIS:
                return $this->getConnectionParameters();
        }

        throw new \ErrorException('Missing implementation for adapter ' . $kvName);
    }

    /**
     * @return array
     */
    protected function getConnectionParameters()
    {
        //TODO: Remove BC defaults
        $config = [
            'protocol' => Config::get(StorageConstants::STORAGE_REDIS_PROTOCOL, Config::get(ApplicationConstants::YVES_STORAGE_SESSION_REDIS_PROTOCOL)),
            'port' => Config::get(StorageConstants::STORAGE_REDIS_PORT, Config::get(ApplicationConstants::YVES_STORAGE_SESSION_REDIS_PORT)),
            'host' => Config::get(StorageConstants::STORAGE_REDIS_HOST, Config::get(ApplicationConstants::YVES_STORAGE_SESSION_REDIS_HOST)),
            'database' => Config::get(StorageConstants::STORAGE_REDIS_DATABASE, static::DEFAULT_REDIS_DATABASE),
        ];

        //TODO: Remove BC elseif
        if (Config::hasKey(StorageConstants::STORAGE_REDIS_PASSWORD)) {
            $config['password'] = Config::get(StorageConstants::STORAGE_REDIS_PASSWORD);
        } elseif (Config::hasKey(ApplicationConstants::YVES_STORAGE_SESSION_REDIS_PASSWORD)) {
            $config['password'] = Config::get(ApplicationConstants::YVES_STORAGE_SESSION_REDIS_PASSWORD);
        }

        //TODO: Remove BC elseif
        $config['persistent'] = false;
        if (Config::hasKey(StorageConstants::STORAGE_PERSISTENT_CONNECTION)) {
            $config['persistent'] = (bool)Config::get(StorageConstants::STORAGE_PERSISTENT_CONNECTION);
        } elseif (Config::hasKey(ApplicationConstants::YVES_STORAGE_SESSION_PERSISTENT_CONNECTION)) {
            $config['password'] = Config::get(ApplicationConstants::YVES_STORAGE_SESSION_PERSISTENT_CONNECTION);
        }

        return $config;
    }

}
