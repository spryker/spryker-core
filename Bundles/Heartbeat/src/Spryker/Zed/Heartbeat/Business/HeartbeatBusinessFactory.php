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
use Spryker\Shared\Session\SessionConstants;
use Spryker\Shared\Storage\StorageConstants;
use Spryker\Zed\Heartbeat\Business\Ambulance\Doctor;
use Spryker\Zed\Heartbeat\Business\Assistant\PropelHealthIndicator;
use Spryker\Zed\Heartbeat\Business\Assistant\SearchHealthIndicator;
use Spryker\Zed\Heartbeat\Business\Assistant\SessionHealthIndicator;
use Spryker\Zed\Heartbeat\Business\Assistant\StorageHealthIndicator;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Storage\StorageConfig;

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
     * @return \Elastica\Client
     */
    protected function createElasticaClient()
    {
        if (Config::hasValue(ApplicationConstants::ELASTICA_CLIENT_CONFIGURATION)) {
            return Config::get(ApplicationConstants::ELASTICA_CLIENT_CONFIGURATION);
        }

        if (Config::hasValue(ApplicationConstants::ELASTICA_PARAMETER__EXTRA)) {
            $config = Config::get(ApplicationConstants::ELASTICA_PARAMETER__EXTRA);
        }

        $config['protocol'] = ucfirst(Config::get(ApplicationConstants::ELASTICA_PARAMETER__TRANSPORT));
        $config['port'] = Config::get(ApplicationConstants::ELASTICA_PARAMETER__PORT);
        $config['host'] = Config::get(ApplicationConstants::ELASTICA_PARAMETER__HOST);

        if (Config::hasValue(ApplicationConstants::ELASTICA_PARAMETER__AUTH_HEADER)) {
            $config['headers'] = [
                'Authorization' => 'Basic ' . Config::get(ApplicationConstants::ELASTICA_PARAMETER__AUTH_HEADER),
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
     * @return \Predis\Client
     */
    protected function createPredisClient()
    {
        $config = $this->getConnectionParameters();
        $options = $this->getPredisClientOptions();

        return new PredisClient($config, $options);
    }

    /**
     * @return array
     */
    protected function getConnectionParameters()
    {
        if (Config::hasKey(StorageConstants::STORAGE_PREDIS_CLIENT_CONFIGURATION)) {
            return Config::get(StorageConstants::STORAGE_PREDIS_CLIENT_CONFIGURATION);
        }

        $config = [
            'protocol' => Config::get(StorageConstants::STORAGE_REDIS_PROTOCOL, Config::get(SessionConstants::ZED_SESSION_REDIS_PROTOCOL)),
            'port' => Config::get(StorageConstants::STORAGE_REDIS_PORT, Config::get(SessionConstants::ZED_SESSION_REDIS_PORT)),
            'host' => Config::get(StorageConstants::STORAGE_REDIS_HOST, Config::get(SessionConstants::ZED_SESSION_REDIS_HOST)),
            'database' => Config::get(StorageConstants::STORAGE_REDIS_DATABASE, StorageConfig::DEFAULT_REDIS_DATABASE),
        ];

        if (Config::hasKey(SessionConstants::ZED_SESSION_REDIS_PASSWORD) &&
            Config::get(SessionConstants::ZED_SESSION_REDIS_PASSWORD) !== false
        ) {
            $config['password'] = Config::get(SessionConstants::ZED_SESSION_REDIS_PASSWORD);
        }

        return $config;
    }

    /**
     * @return mixed|null
     */
    protected function getPredisClientOptions()
    {
        if (Config::hasKey(StorageConstants::STORAGE_PREDIS_CLIENT_OPTIONS)) {
            return Config::get(StorageConstants::STORAGE_PREDIS_CLIENT_OPTIONS);
        }

        return null;
    }
}
