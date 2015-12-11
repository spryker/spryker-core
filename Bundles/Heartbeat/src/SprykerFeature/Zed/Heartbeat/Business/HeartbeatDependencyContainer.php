<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Heartbeat\Business;

use Elastica\Client as ElasticaClient;
use Predis\Client as PredisClient;
use SprykerEngine\Shared\Config;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Shared\Heartbeat\Code\HealthIndicatorInterface;
use SprykerFeature\Zed\Heartbeat\Business\Ambulance\Doctor;
use SprykerFeature\Zed\Heartbeat\Business\Assistant\PropelHealthIndicator;
use SprykerFeature\Zed\Heartbeat\Business\Assistant\SearchHealthIndicator;
use SprykerFeature\Zed\Heartbeat\Business\Assistant\SessionHealthIndicator;
use SprykerFeature\Zed\Heartbeat\Business\Assistant\StorageHealthIndicator;
use SprykerFeature\Zed\Heartbeat\HeartbeatConfig;
use SprykerFeature\Shared\Application\ApplicationConfig;

/**
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
        return new SearchHealthIndicator(
            $this->createElasticaClient()
        );
    }

    /**
     * @throws \Exception
     *
     * @return ElasticaClient
     */
    protected function createElasticaClient()
    {
        $client = new ElasticaClient([
            'protocol' => Config::get(ApplicationConfig::ELASTICA_PARAMETER__TRANSPORT),
            'port' => Config::get(ApplicationConfig::ELASTICA_PARAMETER__PORT),
            'host' => Config::get(ApplicationConfig::ELASTICA_PARAMETER__HOST),
        ]);

        return $client;
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
        return new StorageHealthIndicator(
            $this->createPredisClient()
        );
    }

    /**
     * @throws \Exception
     *
     * @return PredisClient
     */
    protected function createPredisClient()
    {
        $config = [
            'protocol' => Config::get(ApplicationConfig::ZED_STORAGE_SESSION_REDIS_PROTOCOL),
            'port' => Config::get(ApplicationConfig::ZED_STORAGE_SESSION_REDIS_PORT),
            'host' => Config::get(ApplicationConfig::ZED_STORAGE_SESSION_REDIS_HOST),
        ];
        $client = new PredisClient($config);

        return $client;
    }

}
