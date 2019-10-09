<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Http\Plugin\EventDispatcher;

use ArrayObject;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Yves\Http\HttpConfig getConfig()
 * @method \Spryker\Yves\Http\HttpFactory getFactory()
 */
class CookieEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    protected const SERVICE_COOKIES = 'cookies';
    protected const COOKIES_HANDLER_PRIORITY = -255;

    /**
     * {@inheritDoc}
     * - Adds a listener to handle transparent cookie insertion.
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
        $eventDispatcher->addListener(KernelEvents::RESPONSE, function (FilterResponseEvent $event) use ($container): void {
            $cookies = $this->getCookies($container);
            foreach ($cookies as $cookie) {
                $event->getResponse()->headers->setCookie($cookie);
            }
        }, static::COOKIES_HANDLER_PRIORITY);

        return $eventDispatcher;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \ArrayObject
     */
    protected function getCookies(ContainerInterface $container): ArrayObject
    {
        return $container->get(static::SERVICE_COOKIES);
    }
}
