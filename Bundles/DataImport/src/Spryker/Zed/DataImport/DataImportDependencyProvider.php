<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport;

use PHP_Timer;
use Propel\Runtime\Propel;
use Spryker\Zed\DataImport\Dependency\Console\DataImportToConsoleBridge;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventBridge;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToTouchBridge;
use Spryker\Zed\DataImport\Dependency\Propel\DataImportToPropelConnectionBridge;
use Spryker\Zed\DataImport\Dependency\Timer\DataImportToTimerBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;

class DataImportDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_EVENT = 'event facade';
    const FACADE_TOUCH = 'touch facade';
    const PROPEL_CONNECTION = 'propel connection';
    const CONSOLE_LOGGER = 'console logger';
    const TIMER = 'timer';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $this->addEventFacade($container);
        $this->addTouchFacade($container);
        $this->addPropelConnection($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $this->addConsoleLogger($container);
        $this->addTimer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addEventFacade(Container $container)
    {
        $container[static::FACADE_EVENT] = function (Container $container) {
            return new DataImportToEventBridge(
                $container->getLocator()->event()->facade()
            );
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addTouchFacade(Container $container)
    {
        $container[static::FACADE_TOUCH] = function (Container $container) {
            return new DataImportToTouchBridge(
                $container->getLocator()->touch()->facade()
            );
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addConsoleLogger(Container $container)
    {
        $container[static::CONSOLE_LOGGER] = function () {
            return new DataImportToConsoleBridge(
                new ConsoleLogger(new ConsoleOutput(ConsoleOutput::VERBOSITY_VERY_VERBOSE))
            );
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addTimer(Container $container)
    {
        $container[static::TIMER] = function () {
            return new DataImportToTimerBridge(
                new PHP_Timer()
            );
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addPropelConnection(Container $container)
    {
        $container[static::PROPEL_CONNECTION] = function () {
            return new DataImportToPropelConnectionBridge(
                Propel::getConnection()
            );
        };
    }

}
