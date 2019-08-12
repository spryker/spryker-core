<?php

namespace Spryker\Zed\Http\Communication\Plugin\EventDispatcher;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Http\Communication\HttpCommunicationFactory getFactory()
 * @method \Spryker\Zed\Http\HttpConfig getConfig()
 */
class FragmentEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    public function extend(EventDispatcherInterface $eventDispatcher, ContainerInterface $container): EventDispatcherInterface
    {
        // TODO: Implement extend() method.
    }
}
