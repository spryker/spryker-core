<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Storage\Service;

use Generated\Client\Ide\FactoryAutoCompletion\StorageService;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\System\SystemConfig;

/**
 * @method StorageService getFactory()
 */
class StorageDependencyContainer extends AbstractServiceDependencyContainer
{

    /**
     * @return StorageClientInterface
     */
    public function createService()
    {
        return $this->getFactory()->createRedisService(
            $this->getConfig()
        );
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
