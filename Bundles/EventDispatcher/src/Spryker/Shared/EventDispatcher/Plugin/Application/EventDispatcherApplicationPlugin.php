<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\EventDispatcher\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EventDispatcherApplicationPlugin implements ApplicationPluginInterface
{
    public const SERVICE_DISPATCHER = 'dispatcher';

    /**
     * {@inheritdoc}
     * - Adds EventDispatcher to the application.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_DISPATCHER, function (ContainerInterface $container) {
            $dispatcher = $this->createEventDispatcher();

            if ($container->has(static::SERVICE_DISPATCHER)) {
                $existingEventDispatcher = $this->getExistingEventDispatcher($container);
                $dispatcher = $this->addListenersFromExistingEventDispatcher($dispatcher, $existingEventDispatcher);
            }
            $container->remove(static::SERVICE_DISPATCHER);

            return $dispatcher;
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected function getExistingEventDispatcher(ContainerInterface $container): EventDispatcherInterface
    {
        return $container->get(static::SERVICE_DISPATCHER);
    }

    /**
     * @return \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected function createEventDispatcher(): EventDispatcherInterface
    {
        return new EventDispatcher();
    }

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $existingEventDispatcher
     *
     * @return \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected function addListenersFromExistingEventDispatcher(
        EventDispatcherInterface $eventDispatcher,
        EventDispatcherInterface $existingEventDispatcher
    ): EventDispatcherInterface {
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
}
