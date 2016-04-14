<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage;

use Predis\Client;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Storage\Redis\Service;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;

class StorageFactory extends AbstractFactory
{

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
     * @throws \Exception
     *
     * @return array
     */
    protected function getConfig()
    {
        $config = [
            'protocol' => Config::get(ApplicationConstants::YVES_STORAGE_SESSION_REDIS_PROTOCOL),
            'port' => Config::get(ApplicationConstants::YVES_STORAGE_SESSION_REDIS_PORT),
            'host' => Config::get(ApplicationConstants::YVES_STORAGE_SESSION_REDIS_HOST),
            'password' => Config::get(ApplicationConstants::YVES_STORAGE_SESSION_REDIS_PASSWORD),
        ];

        if (Config::hasValue(ApplicationConstants::YVES_STORAGE_SESSION_REDIS_PASSWORD)) {
            $config['password'] = Config::get(ApplicationConstants::YVES_STORAGE_SESSION_REDIS_PASSWORD);
        }

        return $config;
    }

}
