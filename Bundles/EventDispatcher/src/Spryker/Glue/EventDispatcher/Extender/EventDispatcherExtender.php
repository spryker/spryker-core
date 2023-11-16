<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EventDispatcher\Extender;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;

class EventDispatcherExtender implements EventDispatcherExtenderInterface
{
    /**
     * @var string
     */
    protected const SERVICE_DISPATCHER = 'dispatcher';

    /**
     * @var \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    protected EventDispatcherInterface $eventDispatcher;

    /**
     * @var array<\Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface>
     */
    protected array $eventDispatcherPlugins;

    /**
     * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param array<\Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface> $eventDispatcherPlugins
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, array $eventDispatcherPlugins)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->eventDispatcherPlugins = $eventDispatcherPlugins;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function extend(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_DISPATCHER, function (ContainerInterface $container) {
            $eventDispatcher = $this->extendEventDispatcher($this->eventDispatcher, $container);

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
        foreach ($this->eventDispatcherPlugins as $eventDispatcherPlugin) {
            $eventDispatcher = $eventDispatcherPlugin->extend($eventDispatcher, $container);
        }

        return $eventDispatcher;
    }
}
