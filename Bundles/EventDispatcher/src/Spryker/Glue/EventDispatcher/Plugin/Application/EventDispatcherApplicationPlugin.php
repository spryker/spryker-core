<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EventDispatcher\Plugin\Application;

use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;

/**
 * @method \Spryker\Glue\EventDispatcher\EventDispatcherFactory getFactory()
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
        $container->set(static::SERVICE_DISPATCHER, function (ContainerInterface $container) {
            $eventDispatcher = $this->getFactory()->createEventDispatcher();

            $eventDispatcher = $this->extendEventDispatcher($eventDispatcher, $container);

            return $eventDispatcher;
        });

        return $container;
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
