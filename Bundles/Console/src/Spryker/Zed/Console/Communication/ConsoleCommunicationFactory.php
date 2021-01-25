<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Console\Communication;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Application\Application;
use Spryker\Shared\Application\ApplicationInterface;
use Spryker\Shared\Kernel\Container\ContainerProxy;
use Spryker\Zed\Console\ConsoleDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @method \Spryker\Zed\Console\ConsoleConfig getConfig()
 * @method \Spryker\Zed\Console\Business\ConsoleFacadeInterface getFacade()
 */
class ConsoleCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Shared\Application\ApplicationInterface
     */
    public function createApplication(): ApplicationInterface
    {
        return new Application($this->createServiceContainer(), $this->getApplicationPlugins());
    }

    /**
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function createServiceContainer(): ContainerInterface
    {
        return new ContainerProxy(['logger' => null, 'debug' => $this->getConfig()->isDebugModeEnabled(), 'charset' => 'UTF-8']);
    }

    /**
     * @return \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface[]
     */
    public function getApplicationPlugins(): array
    {
        return $this->getProvidedDependency(ConsoleDependencyProvider::PLUGINS_APPLICATION);
    }

    /**
     * @return \Symfony\Component\Console\Command\Command[]
     */
    public function getConsoleCommands(): array
    {
        return $this->getProvidedDependency(ConsoleDependencyProvider::COMMANDS);
    }

    /**
     * @return \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    public function createEventDispatcher(): EventDispatcherInterface
    {
        return new EventDispatcher();
    }

    /**
     * @return \Symfony\Component\EventDispatcher\EventSubscriberInterface[]
     */
    public function getEventSubscriber(): array
    {
        return $this->getProvidedDependency(ConsoleDependencyProvider::EVENT_SUBSCRIBER);
    }
}
