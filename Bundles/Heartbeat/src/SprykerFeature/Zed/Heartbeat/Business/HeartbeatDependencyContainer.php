<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Heartbeat\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\HeartbeatBusiness;
use Elastica\Client as ElasticaClient;
use Predis\Client as PedicClient;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Shared\Heartbeat\Code\HealthIndicatorInterface;
use SprykerFeature\Zed\Heartbeat\Business\Ambulance\Doctor;
use SprykerFeature\Zed\Heartbeat\Business\Assistant\PropelHealthIndicator;
use SprykerFeature\Zed\Heartbeat\Business\Assistant\SearchHealthIndicator;
use SprykerFeature\Zed\Heartbeat\Business\Assistant\SessionHealthIndicator;
use SprykerFeature\Zed\Heartbeat\Business\Assistant\StorageHealthIndicator;
use SprykerFeature\Zed\Heartbeat\HeartbeatConfig;
use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\System\SystemConfig;

/**
 * @method HeartbeatBusiness getFactory()
 * @method HeartbeatConfig getConfig()
 */
class HeartbeatDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return Doctor
     */
    public function createDoctor()
    {
        return new Doctor(
            $this->getConfig()->getHealthIndicator()
        );
    }

    /**
     * @return HealthIndicatorInterface
     */
    public function createPropelHealthIndicator()
    {
        return new PropelHealthIndicator();
    }

    /**
     * @return HealthIndicatorInterface
     */
    public function createSearchHealthIndicator()
    {
        $client = new ElasticaClient([
            'protocol' => Config::get(SystemConfig::ELASTICA_PARAMETER__TRANSPORT),
            'port' => Config::get(SystemConfig::ELASTICA_PARAMETER__PORT),
            'host' => Config::get(SystemConfig::ELASTICA_PARAMETER__HOST),
        ]);

        return new SearchHealthIndicator($client);
    }

    /**
     * @return HealthIndicatorInterface
     */
    public function createSessionHealthIndicator()
    {
        return new SessionHealthIndicator();
    }

    /**
     * @return HealthIndicatorInterface
     */
    public function createStorageHealthIndicator()
    {
        $config = [
            'protocol' => Config::get(SystemConfig::ZED_STORAGE_SESSION_REDIS_PROTOCOL),
            'port' => Config::get(SystemConfig::ZED_STORAGE_SESSION_REDIS_PORT),
            'host' => Config::get(SystemConfig::ZED_STORAGE_SESSION_REDIS_HOST),
        ];
        $client = new PedicClient($config);

        return new StorageHealthIndicator($client);
    }

}
