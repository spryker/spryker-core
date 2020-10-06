<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedRequest\Communication\Plugin\EventDispatcher;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ZedRequest\Communication\Plugin\TransferObject\TransferServer;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Zed\ZedRequest\Communication\ZedRequestCommunicationFactory getFactory()
 * @method \Spryker\Zed\ZedRequest\Business\ZedRequestFacadeInterface getFacade()
 * @method \Spryker\Zed\ZedRequest\ZedRequestConfig getConfig()
 */
class GatewayControllerEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds a listener for the `\Symfony\Component\HttpKernel\KernelEvents::CONTROLLER` event which will wrap calls to GatewayController into a callable.
     * - Adds a listener for the `\Symfony\Component\HttpKernel\KernelEvents::REQUEST` event which sets the current request into the TransferServer.
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
        $eventDispatcher->addListener(KernelEvents::CONTROLLER, function (ControllerEvent $event) {
            return $this->getFactory()->createControllerListener()->onKernelController($event);
        });

        $eventDispatcher->addListener(KernelEvents::REQUEST, function (RequestEvent $event): void {
            TransferServer::getInstance()->setRequest($event->getRequest());
        });

        return $eventDispatcher;
    }
}
