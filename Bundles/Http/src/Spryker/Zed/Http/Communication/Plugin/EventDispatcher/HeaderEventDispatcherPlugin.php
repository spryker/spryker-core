<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Http\Communication\Plugin\EventDispatcher;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Zed\Http\Communication\HttpCommunicationFactory getFactory()
 * @method \Spryker\Zed\Http\HttpConfig getConfig()
 */
class HeaderEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    protected const HEADER_HANDLER_PRIORITY = 0;

    /**
     * {@inheritdoc}
     * - Sets store main information in headers.
     * - Sets cache control.
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
        $eventDispatcher->addListener(KernelEvents::RESPONSE, function (FilterResponseEvent $event) {
            $this->onKernelResponse($event);
        }, static::HEADER_HANDLER_PRIORITY);

        return $eventDispatcher;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event A FilterResponseEvent instance
     *
     * @return void
     */
    protected function onKernelResponse(FilterResponseEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $response = $event->getResponse();

        $localeFacade = $this->getFactory()->getLocaleFacade();

        $response->headers->set('X-Store', APPLICATION_STORE);
        $response->headers->set('X-Env', APPLICATION_ENV);
        $response->headers->set('X-Locale', $localeFacade->getCurrentLocale()->getLocaleName());

        $response->setPrivate()->setMaxAge(0);

        $response->headers->addCacheControlDirective('no-cache', true);
        $response->headers->addCacheControlDirective('no-store', true);
        $response->headers->addCacheControlDirective('must-revalidate', true);
    }
}
