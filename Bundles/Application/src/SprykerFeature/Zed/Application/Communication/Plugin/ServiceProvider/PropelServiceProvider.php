<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider;

use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\System\SystemConfig;
use Propel\Runtime\Connection\ConnectionManagerSingle;
use Propel\Runtime\Propel;
use Propel\Runtime\ServiceContainer\StandardServiceContainer;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

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
        $serviceContainer->setAdapterClass('zed', 'pgsql');
        $serviceContainer->setConnectionManager('zed', $manager);
        $serviceContainer->setDefaultDatasource('zed');

        $this->addLogger($serviceContainer);
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
     */
    private function addLogger(StandardServiceContainer $serviceContainer)
    {
        $defaultLogger = new Logger('defaultLogger');
        $pathToLogFile = APPLICATION_ROOT_DIR . '/data/'
            . \SprykerEngine\Shared\Kernel\Store::getInstance()->getStoreName()
            . '/logs/ZED/propel.log'
        ;

        $defaultLogger->pushHandler(new StreamHandler(
            $pathToLogFile,
            Logger::WARNING
        ));

        $serviceContainer->setLogger('defaultLogger', $defaultLogger);
    }

}
