<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Propel\Communication\Plugin\ServiceProvider;

use Propel\Runtime\Connection\ConnectionManagerSingle;
use Propel\Runtime\Propel;
use Propel\Runtime\ServiceContainer\StandardServiceContainer;
use Silex\Application;
use Silex\ServiceProviderInterface;
use SprykerEngine\Shared\Config;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerEngine\Zed\Kernel\Communication\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Propel\Communication\PropelDependencyContainer;
use SprykerFeature\Shared\System\SystemConfig;

/**
 * @method PropelDependencyContainer getDependencyContainer()
 */
class PropelServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{

    const BUNDLE = 'Propel';

    /**
     * ServiceProvider is created with new in many places e.g. tests
     * To setup the Plugin, we have to have a factory and the locator.
     * To prevent doing this in all places, we add them here.
     */
    public function __construct()
    {
        parent::__construct(new Factory(self::BUNDLE), Locator::getInstance());
    }

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

        $serviceContainer = $this->getServiceContainer();
        $serviceContainer->setAdapterClass('zed', Config::get(SystemConfig::ZED_DB_ENGINE));
        $serviceContainer->setConnectionManager('zed', $manager);
        $serviceContainer->setDefaultDatasource('zed');

        $this->addLogger($serviceContainer);

        if (Config::get(SystemConfig::PROPEL_DEBUG) && $this->hasConnection()) {
            $connection = Propel::getConnection();
            $connection->useDebug(true);
        }
    }

    /**
     * @return StandardServiceContainer
     */
    protected function getServiceContainer()
    {
        $serviceContainer = Propel::getServiceContainer();

        return $serviceContainer;
    }

    /**
     * Allowed try/catch. If we have no database setup, getConnection throws an Exception
     * ServiceProvider is called more then once and after setup of database we can enable debug
     *
     * @return bool
     */
    private function hasConnection()
    {
        try {
            $connection = Propel::getConnection();

            return true;
        } catch (\Exception $e) {
            return false;
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
     *
     * @throws \ErrorException
     */
    private function addLogger(StandardServiceContainer $serviceContainer)
    {
        $loggerCollection = $this->getDependencyContainer()->createLogger();

        foreach ($loggerCollection as $logger) {
            $serviceContainer->setLogger($logger->getName(), $logger);
        }
    }

}
