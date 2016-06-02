<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Heartbeat\Business;

use Elastica\Client as ElasticaClient;
use Predis\Client as PredisClient;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;
use Spryker\Zed\Heartbeat\Business\Ambulance\Doctor;
use Spryker\Zed\Heartbeat\Business\Assistant\PropelHealthIndicator;
use Spryker\Zed\Heartbeat\Business\Assistant\SearchHealthIndicator;
use Spryker\Zed\Heartbeat\Business\Assistant\SessionHealthIndicator;
use Spryker\Zed\Heartbeat\Business\Assistant\StorageHealthIndicator;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Heartbeat\HeartbeatConfig getConfig()
 */
class HeartbeatBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Heartbeat\Business\Ambulance\Doctor
     */
    public function createDoctor()
    {
        return new Doctor(
            $this->getConfig()->getHealthIndicator()
        );
    }

    /**
     * @return \Spryker\Shared\Heartbeat\Code\HealthIndicatorInterface
     */
    public function createPropelHealthIndicator()
    {
        return new PropelHealthIndicator();
    }

    /**
     * @return \Spryker\Shared\Heartbeat\Code\HealthIndicatorInterface
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
     * @return \Elastica\Client
     */
    protected function createElasticaClient()
    {
        $config = [
            'protocol' => ucfirst(Config::get(ApplicationConstants::ELASTICA_PARAMETER__TRANSPORT)),
            'port' => Config::get(ApplicationConstants::ELASTICA_PARAMETER__PORT),
            'host' => Config::get(ApplicationConstants::ELASTICA_PARAMETER__HOST),
        ];

        if (Config::hasKey(ApplicationConstants::ELASTICA_PARAMETER__AUTH_HEADER) && Config::hasValue(ApplicationConstants::ELASTICA_PARAMETER__AUTH_HEADER)) {
            $config['headers'] = [
                'Authorization' => 'Basic '.Config::get(ApplicationConstants::ELASTICA_PARAMETER__AUTH_HEADER)
            ];
        }

        $client = new ElasticaClient($config);

        return $client;
    }

    /**
     * @return \Spryker\Shared\Heartbeat\Code\HealthIndicatorInterface
     */
    public function createSessionHealthIndicator()
    {
        return new SessionHealthIndicator();
    }

    /**
     * @return \Spryker\Shared\Heartbeat\Code\HealthIndicatorInterface
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
     * @return \Predis\Client
     */
    protected function createPredisClient()
    {
        $config =  $this->getConnectionParameters();

        return new PredisClient($config);
    }


    /**
     * @return array
     */
    protected function getConnectionParameters()
    {
        $config = [
            'protocol' => Config::get(ApplicationConstants::ZED_STORAGE_SESSION_REDIS_PROTOCOL),
            'port' => Config::get(ApplicationConstants::ZED_STORAGE_SESSION_REDIS_PORT),
            'host' => Config::get(ApplicationConstants::ZED_STORAGE_SESSION_REDIS_HOST),
        ];

        $password = Config::getValueByName('ApplicationConstants::ZED_STORAGE_SESSION_REDIS_PASSWORD');
        if ($password !== null) {
            $config['password'] = $password;
        }

        return $config;
    }

}
