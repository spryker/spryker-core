<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\EventDispatcher\Communication\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as SymfonyEventDispatcherInterface;

/**
 * @method \Spryker\Zed\EventDispatcher\Communication\EventDispatcherCommunicationFactory getFactory()
 * @method \Spryker\Zed\EventDispatcher\EventDispatcherConfig getConfig()
 */
class EventDispatcherApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    public const SERVICE_DISPATCHER = 'dispatcher';

    /**
     * {@inheritDoc}
     * - Extends EventDispatcher with EventDispatcherExtensionPlugins.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        if ($container->has(static::SERVICE_DISPATCHER)) {
            return $this->extendExistingEventDispatcher($container);
        }

        $container->set(static::SERVICE_DISPATCHER, function (ContainerInterface $container) {
            $eventDispatcher = $this->getFactory()->createEventDispatcher();

            $eventDispatcher = $this->extendEventDispatcher($eventDispatcher, $container);

            return $eventDispatcher;
        });

        return $container;
    }

    /**
     * @deprecated This method exists only for BC reasons. We need to make sure that if a dispatcher was already added (e.g. Silex\Application::__construct()) that all listeners attached to it copied to the new EventDispatcher.
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function extendExistingEventDispatcher(ContainerInterface $container): ContainerInterface
    {
        $container->extend(static::SERVICE_DISPATCHER, function (SymfonyEventDispatcherInterface $existingEventDispatcher, ContainerInterface $container) {
            $eventDispatcher = $this->getFactory()->createEventDispatcher();

            $eventDispatcher = $this->copyExistingListeners($eventDispatcher, $existingEventDispatcher);

            $eventDispatcher = $this->extendEventDispatcher($eventDispatcher, $container);

            return $eventDispatcher;
        });

        return $container;
    }

    /**
     * @deprecated This method exists only for BC reasons. We need to make sure that if a dispatcher was already added (e.g. Silex\Application::__construct()) that all listeners attached to it copied to the new EventDispatcher.
     *
     * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $existingEventDispatcher
     *
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    protected function copyExistingListeners(EventDispatcherInterface $eventDispatcher, SymfonyEventDispatcherInterface $existingEventDispatcher): EventDispatcherInterface
    {
        $existingListeners = $existingEventDispatcher->getListeners();

        foreach ($existingListeners as $eventName => $eventListeners) {
            foreach ($eventListeners as $listener) {
                $eventDispatcher->addListener(
                    $eventName,
                    $listener,
                    $existingEventDispatcher->getListenerPriority($eventName, $listener)
                );
            }
        }

        return $eventDispatcher;
    }

    /**
     * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    protected function extendEventDispatcher(EventDispatcherInterface $eventDispatcher, ContainerInterface $container): EventDispatcherInterface
    {
        foreach ($this->getFactory()->getEventDispatcherPlugins() as $eventDispatcherPlugin) {
            $eventDispatcher = $eventDispatcherPlugin->extend($eventDispatcher, $container);
        }

        return $eventDispatcher;
    }
}
