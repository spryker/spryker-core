<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider;

use Propel\Runtime\Connection\ConnectionManagerSingle;
use Propel\Runtime\Propel;
use Propel\Runtime\ServiceContainer\StandardServiceContainer;
use Silex\Application;
use Silex\ServiceProviderInterface;
use SprykerEngine\Shared\Config;
use SprykerFeature\Shared\System\SystemConfig;

class PropelServiceProvider implements ServiceProviderInterface
{

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
    }

    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
        $manager = new ConnectionManagerSingle();
        $manager->setConfiguration($this->getConfig());
        $manager->setName('zed');

        /** @var StandardServiceContainer $serviceContainer */
        $serviceContainer = Propel::getServiceContainer();
        $serviceContainer->setAdapterClass('zed', Config::get(SystemConfig::ZED_DB_ENGINE));
        $serviceContainer->setConnectionManager('zed', $manager);
        $serviceContainer->setDefaultDatasource('zed');

        $this->addLogger($serviceContainer);

        $debug = Config::get(SystemConfig::PROPEL_DEBUG);

        if (true === $debug) {
            $con = Propel::getConnection();
            $con->useDebug(true);
        }
    }

    /**
     * @throws \Exception
     *
     * @return mixed
     */
    private function getConfig()
    {
        $propelConfig = Config::get(SystemConfig::PROPEL)['database']['connections']['default'];
        $propelConfig['user'] = Config::get(SystemConfig::ZED_DB_USERNAME);
        $propelConfig['password'] = Config::get(SystemConfig::ZED_DB_PASSWORD);
        $propelConfig['dsn'] = Config::get(SystemConfig::PROPEL)['database']['connections']['default']['dsn'];


        return $propelConfig;
    }

    /**
     * @param StandardServiceContainer $serviceContainer
     * @throws \ErrorException
     */
    private function addLogger(StandardServiceContainer $serviceContainer)
    {
        $loggers = Config::get(SystemConfig::PROPEL_LOGGER);

        if (is_array($loggers)) {
            foreach ($loggers as $logger) {
                $serviceContainer->setLogger($logger->getName(), $logger);
            }
        } else {
            throw new \ErrorException('PROPEL_LOGGER must be an array');
        }
    }
}
