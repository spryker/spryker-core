<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\StorageHeartbeatConnector\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\StorageHeartbeatConnectorBusiness;
use Predis\Client;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Shared\Heartbeat\Code\HealthIndicatorInterface;
use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\System\SystemConfig;

/**
 * @method StorageHeartbeatConnectorBusiness getFactory()
 */
class StorageHeartbeatConnectorDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return HealthIndicatorInterface
     */
    public function createHealthIndicator()
    {
        $config = [
            'protocol' => Config::get(SystemConfig::ZED_STORAGE_SESSION_REDIS_PROTOCOL),
            'port' => Config::get(SystemConfig::ZED_STORAGE_SESSION_REDIS_PORT),
            'host' => Config::get(SystemConfig::ZED_STORAGE_SESSION_REDIS_HOST),
        ];
        $client = new Client($config);

        return $this->getFactory()->createAssistantStorageHealthIndicator($client);
    }

}
