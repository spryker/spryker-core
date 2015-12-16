<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Heartbeat\Business;

use Elastica\Client as ElasticaClient;
use Predis\Client as PredisClient;
use Spryker\Shared\Config;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Shared\Heartbeat\Code\HealthIndicatorInterface;
use Spryker\Zed\Heartbeat\Business\Ambulance\Doctor;
use Spryker\Zed\Heartbeat\Business\Assistant\PropelHealthIndicator;
use Spryker\Zed\Heartbeat\Business\Assistant\SearchHealthIndicator;
use Spryker\Zed\Heartbeat\Business\Assistant\SessionHealthIndicator;
use Spryker\Zed\Heartbeat\Business\Assistant\StorageHealthIndicator;
use Spryker\Zed\Heartbeat\HeartbeatConfig;
use Spryker\Shared\Application\ApplicationConstants;

/**
 * @method HeartbeatConfig getConfig()
 */
class HeartbeatDependencyContainer extends AbstractBusinessFactory
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
            'protocol' => Config::get(ApplicationConstants::ELASTICA_PARAMETER__TRANSPORT),
            'port' => Config::get(ApplicationConstants::ELASTICA_PARAMETER__PORT),
            'host' => Config::get(ApplicationConstants::ELASTICA_PARAMETER__HOST),
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
            'protocol' => Config::get(ApplicationConstants::ZED_STORAGE_SESSION_REDIS_PROTOCOL),
            'port' => Config::get(ApplicationConstants::ZED_STORAGE_SESSION_REDIS_PORT),
            'host' => Config::get(ApplicationConstants::ZED_STORAGE_SESSION_REDIS_HOST),
        ];
        $client = new PredisClient($config);

        return $client;
    }

}
