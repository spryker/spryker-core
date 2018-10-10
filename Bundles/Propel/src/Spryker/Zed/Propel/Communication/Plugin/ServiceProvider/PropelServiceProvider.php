<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Plugin\ServiceProvider;

use Exception;
use Propel\Runtime\Connection\ConnectionManagerSingle;
use Propel\Runtime\Propel;
use Propel\Runtime\ServiceContainer\StandardServiceContainer;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Propel\Communication\PropelCommunicationFactory getFactory()
 * @method \Spryker\Zed\Propel\Business\PropelFacadeInterface getFacade()
 */
class PropelServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    public const BUNDLE = 'Propel';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
        $manager = new ConnectionManagerSingle();
        $manager->setConfiguration($this->getPropelConfig());
        $manager->setName('zed');

        $serviceContainer = $this->getServiceContainer();
        $serviceContainer->setAdapterClass('zed', Config::get(PropelConstants::ZED_DB_ENGINE));
        $serviceContainer->setConnectionManager('zed', $manager);
        $serviceContainer->setDefaultDatasource('zed');

        $this->addLogger($serviceContainer);

        if (Config::get(PropelConstants::PROPEL_DEBUG, false) && $this->hasConnection()) {
            /** @var \Propel\Runtime\Connection\ConnectionWrapper $connection */
            $connection = Propel::getConnection();
            $connection->useDebug(true);
        }
    }

    /**
     * @return \Propel\Runtime\ServiceContainer\StandardServiceContainer
     */
    protected function getServiceContainer()
    {
        /** @var \Propel\Runtime\ServiceContainer\StandardServiceContainer $serviceContainer */
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
            Propel::getConnection();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @return array
     */
    private function getPropelConfig()
    {
        $propelConfig = Config::get(PropelConstants::PROPEL)['database']['connections']['default'];
        $propelConfig['user'] = Config::get(PropelConstants::ZED_DB_USERNAME);
        $propelConfig['password'] = Config::get(PropelConstants::ZED_DB_PASSWORD);
        $propelConfig['dsn'] = Config::get(PropelConstants::PROPEL)['database']['connections']['default']['dsn'];

        return $propelConfig;
    }

    /**
     * @param \Propel\Runtime\ServiceContainer\StandardServiceContainer $serviceContainer
     *
     * @return void
     */
    private function addLogger(StandardServiceContainer $serviceContainer)
    {
        $loggerCollection = $this->getFactory()->createLogger();

        foreach ($loggerCollection as $logger) {
            $serviceContainer->setLogger($logger->getName(), $logger);
        }
    }
}
