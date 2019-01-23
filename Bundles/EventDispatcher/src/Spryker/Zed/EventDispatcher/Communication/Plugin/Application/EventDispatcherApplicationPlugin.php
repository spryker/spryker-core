<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\EventDispatcher\Communication\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @method \Spryker\Zed\EventDispatcher\Communication\EventDispatcherCommunicationFactory getFactory()
 * @method \Spryker\Zed\EventDispatcher\EventDispatcherConfig getConfig()
 */
class EventDispatcherApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    public const SERVICE_DISPATCHER = 'dispatcher';

    /**
     * {@inheritdoc}
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
        $container->extend(static::SERVICE_DISPATCHER, function (EventDispatcherInterface $dispatcher, ContainerInterface $container) {
            foreach ($this->getFactory()->getEventDispatcherExtensionPlugins() as $eventDispatcherExtensionPlugin) {
                $dispatcher->addSubscriber($eventDispatcherExtensionPlugin->getSubscruiber($container));
            }

            return $dispatcher;
        });

        return $container;
    }
}
