<?php
/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Plugin\EventDispatcher;

use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\Kernel\Controller\AbstractController;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Glue\GlueApplication\GlueApplicationFactory getFactory()
 */
class GlueRestControllerListenerEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    /**
     * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    public function extend(EventDispatcherInterface $eventDispatcher, ContainerInterface $container): EventDispatcherInterface
    {
        $eventDispatcher->addListener(KernelEvents::CONTROLLER, function (FilterControllerEvent $event) {
            $this->onKernelController($event);
        });

        return $eventDispatcher;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     *
     * @return void
     */
    protected function onKernelController(FilterControllerEvent $event): void
    {
        $currentController = $event->getController();

        [$controller, $action] = $currentController;

        $request = $event->getRequest();

        $apiController = function () use ($controller, $action, $request) {
            return $this->filter($controller, $action, $request);
        };

        $event->setController($apiController);
    }

    /**
     * @param \Spryker\Glue\Kernel\Controller\AbstractController $controller
     * @param string $action
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function filter(AbstractController $controller, string $action, Request $request): Response
    {
        return $this->getFactory()->createRestControllerFilter()->filter($controller, $action, $request);
    }
}
