<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Storage\Service;

use SprykerFeature\Client\Storage\Service\Redis\Service;
use Generated\Client\Ide\FactoryAutoCompletion\StorageService;
use Predis\Client;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\System\SystemConfig;

class StorageDependencyContainer extends AbstractServiceDependencyContainer
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
    private function getConfig()
    {
        return [
            'protocol' => Config::get(SystemConfig::YVES_STORAGE_SESSION_REDIS_PROTOCOL),
            'port' => Config::get(SystemConfig::YVES_STORAGE_SESSION_REDIS_PORT),
            'host' => Config::get(SystemConfig::YVES_STORAGE_SESSION_REDIS_HOST),
        ];
    }

}
