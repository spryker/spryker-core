<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Plugin\Application;

use Exception;
use Propel\Runtime\Connection\ConnectionManagerSingle;
use Propel\Runtime\Propel;
use Propel\Runtime\ServiceContainer\StandardServiceContainer;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Propel\Communication\PropelCommunicationFactory getFactory()
 * @method \Spryker\Zed\Propel\Business\PropelFacadeInterface getFacade()
 * @method \Spryker\Zed\Propel\PropelConfig getConfig()
 */
class PropelApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    protected const DATA_SOURCE_NAME = 'zed';

    /**
     * {@inheritDoc}
     * - Initializes PropelOrm to be used within Zed.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $manager = new ConnectionManagerSingle();
        $manager->setConfiguration($this->getPropelConfig());
        $manager->setName(static::DATA_SOURCE_NAME);

        $serviceContainer = $this->getServiceContainer();
        $serviceContainer->setAdapterClass(static::DATA_SOURCE_NAME, $this->getConfig()->getCurrentDatabaseEngine());
        $serviceContainer->setConnectionManager(static::DATA_SOURCE_NAME, $manager);
        $serviceContainer->setDefaultDatasource(static::DATA_SOURCE_NAME);

        $this->addLogger($serviceContainer);

        if ($this->getConfig()->isDebugEnabled() && $this->hasConnection()) {
            /** @var \Propel\Runtime\Connection\ConnectionWrapper $connection */
            $connection = Propel::getConnection();
            $connection->useDebug(true);
        }

        return $container;
    }

    /**
     * @return \Propel\Runtime\ServiceContainer\StandardServiceContainer
     */
    protected function getServiceContainer(): StandardServiceContainer
    {
        /** @var \Propel\Runtime\ServiceContainer\StandardServiceContainer $serviceContainer */
        $serviceContainer = Propel::getServiceContainer();

        return $serviceContainer;
    }

    /**
     * @return bool
     */
    private function hasConnection(): bool
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
    private function getPropelConfig(): array
    {
        $propelConfig = $this->getConfig()->getPropelConfig()['database']['connections']['default'];
        $propelConfig['user'] = $this->getConfig()->getUsername();
        $propelConfig['password'] = $this->getConfig()->getPassword();
        $propelConfig['dsn'] = $this->getConfig()->getPropelConfig()['database']['connections']['default']['dsn'];

        return $propelConfig;
    }

    /**
     * @param \Propel\Runtime\ServiceContainer\StandardServiceContainer $serviceContainer
     *
     * @return void
     */
    private function addLogger(StandardServiceContainer $serviceContainer): void
    {
        $loggerCollection = $this->getFactory()->createLogger();

        foreach ($loggerCollection as $logger) {
            $serviceContainer->setLogger($logger->getName(), $logger);
        }
    }
}
