<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\KvStorage\Service;

use Generated\Client\Ide\FactoryAutoCompletion\KvStorage;
use SprykerEngine\Client\Kernel\Service\AbstractDependencyContainer;
use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\System\SystemConfig;

/**
 * @method KvStorage getFactory()
 */
class KvStorageDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return KvStorageClientInterface
     */
    public function createService()
    {
        return $this->getFactory()->createRedisService(
            $this->getConfig()
        );
    }

    /**
     * @throws \Exception
     * @return array
     */
    private function getConfig()
    {
        return [
            'protocol' => Config::get(SystemConfig::YVES_STORAGE_SESSION_REDIS_PROTOCOL),
            'port' => Config::get(SystemConfig::YVES_STORAGE_SESSION_REDIS_PORT),
            'host' => Config::get(SystemConfig::YVES_STORAGE_SESSION_REDIS_HOST)
        ];
    }

}
