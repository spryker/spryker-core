<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Propel\Communication\Command\Config\PropelCommandConfigurator;
use Spryker\Zed\Propel\Communication\Command\Config\PropelCommandConfiguratorInterface;
use Spryker\Zed\Propel\Communication\Command\Input\PropelCommandInputBuilder;
use Spryker\Zed\Propel\Communication\Command\Input\PropelCommandInputBuilderInterface;
use Spryker\Zed\Propel\Communication\Command\Runner\PropelCommandRunner;
use Spryker\Zed\Propel\Communication\Command\Runner\PropelCommandRunnerInterface;
use Spryker\Zed\Propel\Dependency\Facade\PropelToTransferFacadeInterface;
use Spryker\Zed\Propel\PropelDependencyProvider;
use Spryker\Zed\PropelOrm\Communication\Generator\Command\MigrationDiffCommand;
use Spryker\Zed\PropelOrm\Communication\Generator\Command\MigrationMigrateCommand;
use Spryker\Zed\PropelOrm\Communication\Generator\Command\MigrationStatusCommand;
use Spryker\Zed\PropelOrm\Communication\Generator\Command\ModelBuildCommand;
use Spryker\Zed\PropelOrm\Communication\Generator\Command\SqlBuildCommand;
use Spryker\Zed\PropelOrm\Communication\Generator\Command\SqlInsertCommand;
use Symfony\Component\Console\Command\Command;

/**
 * @method \Spryker\Zed\Propel\PropelConfig getConfig()
 * @method \Spryker\Zed\Propel\Business\PropelFacadeInterface getFacade()
 */
class PropelCommunicationFactory extends AbstractCommunicationFactory
{
    public const LOGGER_NAME = 'defaultLogger';

    /**
     * @return \Monolog\Logger[]
     */
    public function createLogger()
    {
        $defaultLogger = new Logger(self::LOGGER_NAME);
        $defaultLogger->pushHandler(
            $this->createStreamHandler()
        );

        return [$defaultLogger];
    }

    /**
     * @return \Monolog\Handler\StreamHandler
     */
    protected function createStreamHandler()
    {
        return new StreamHandler(
            $this->getConfig()->getLogPath()
        );
    }

    /**
     * @return \Spryker\Zed\Propel\Dependency\Facade\PropelToLogInterface
     */
    public function getLogFacade()
    {
        return $this->getProvidedDependency(PropelDependencyProvider::FACADE_LOG);
    }

    /**
     * @return \Spryker\Zed\Propel\Communication\Command\Runner\PropelCommandRunnerInterface
     */
    public function createPropelCommandRunner(): PropelCommandRunnerInterface
    {
        return new PropelCommandRunner(
            $this->createPropelCommandInputBuilder(),
            $this->createPropelCommandConfigurator()
        );
    }

    /**
     * @return \Spryker\Zed\Propel\Communication\Command\Config\PropelCommandConfiguratorInterface
     */
    public function createPropelCommandConfigurator(): PropelCommandConfiguratorInterface
    {
        return new PropelCommandConfigurator(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Propel\Communication\Command\Input\PropelCommandInputBuilderInterface
     */
    public function createPropelCommandInputBuilder(): PropelCommandInputBuilderInterface
    {
        return new PropelCommandInputBuilder();
    }

    /**
     * @return \Symfony\Component\Console\Command\Command
     */
    public function createMigrationDiffCommand(): Command
    {
        return new MigrationDiffCommand();
    }

    /**
     * @return \Symfony\Component\Console\Command\Command
     */
    public function createMigrationMigrateCommand(): Command
    {
        return new MigrationMigrateCommand();
    }

    /**
     * @return \Symfony\Component\Console\Command\Command
     */
    public function createMigrationStatusCommand(): Command
    {
        return new MigrationStatusCommand();
    }

    /**
     * @return \Symfony\Component\Console\Command\Command
     */
    public function createModelBuildCommand(): Command
    {
        return new ModelBuildCommand();
    }

    /**
     * @return \Symfony\Component\Console\Command\Command
     */
    public function createSqlBuildCommand(): Command
    {
        return new SqlBuildCommand();
    }

    /**
     * @return \Symfony\Component\Console\Command\Command
     */
    public function createSqlInsertCommand(): Command
    {
        return new SqlInsertCommand();
    }

    /**
     * @return \Spryker\Zed\Propel\Dependency\Facade\PropelToTransferFacadeInterface
     */
    public function getTransferFacade(): PropelToTransferFacadeInterface
    {
        return $this->getProvidedDependency(PropelDependencyProvider::FACADE_TRANSFER);
    }
}
