<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Storage;

use Spryker\Client\Storage\Redis\Service;
use Predis\Client;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Shared\Config;
use Spryker\Shared\Application\ApplicationConstants;

class StorageFactory extends AbstractFactory
{

    /**
     * @return StorageClientInterface
     */
    public function createService()
    {
        return new Service(
            $this->createClient()
        );
    }

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
        return [
            'protocol' => Config::get(ApplicationConstants::YVES_STORAGE_SESSION_REDIS_PROTOCOL),
            'port' => Config::get(ApplicationConstants::YVES_STORAGE_SESSION_REDIS_PORT),
            'host' => Config::get(ApplicationConstants::YVES_STORAGE_SESSION_REDIS_HOST),
        ];
    }

}
