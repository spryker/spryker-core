<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Propel\Communication\Command\Builder\PropelCommandBuilder;
use Spryker\Zed\Propel\Communication\Command\Builder\PropelCommandBuilderInterface;
use Spryker\Zed\Propel\Communication\Command\Config\PropelCommandConfigBuilder;
use Spryker\Zed\Propel\Communication\Command\Config\PropelCommandConfigBuilderInterface;
use Spryker\Zed\Propel\Communication\Command\Input\PropelCommandInputBuilder;
use Spryker\Zed\Propel\Communication\Command\Input\PropelCommandInputBuilderInterface;
use Spryker\Zed\Propel\Communication\Command\Runner\PropelCommandRunner;
use Spryker\Zed\Propel\Communication\Command\Runner\PropelCommandRunnerInterface;
use Spryker\Zed\Propel\PropelDependencyProvider;

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
            $this->createPropelCommandInputBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\Propel\Communication\Command\Builder\PropelCommandBuilderInterface
     */
    public function createPropelCommandCreator(): PropelCommandBuilderInterface
    {
        return new PropelCommandBuilder(
            $this->createPropelCommandConfigurator()
        );
    }

    /**
     * @return \Spryker\Zed\Propel\Communication\Command\Config\PropelCommandConfigBuilderInterface
     */
    protected function createPropelCommandConfigurator(): PropelCommandConfigBuilderInterface
    {
        return new PropelCommandConfigBuilder(
            $this->getConfig()->getPropelConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Propel\Communication\Command\Input\PropelCommandInputBuilderInterface
     */
    protected function createPropelCommandInputBuilder(): PropelCommandInputBuilderInterface
    {
        return new PropelCommandInputBuilder();
    }
}
