<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Http\Plugin\EventDispatcher;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Yves\Http\HttpConfig getConfig()
 * @method \Spryker\Yves\Http\HttpFactory getFactory()
 */
class SslEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    protected const EVENT_PRIORITY_ON_KERNEL_REQUEST = 255;

    /**
     * {@inheritdoc}
     * - Adds a listener to force SSL if enabled.
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
        if ($this->getConfig()->isSslEnabled()) {
            $eventDispatcher->addListener(KernelEvents::REQUEST, function (GetResponseEvent $event) {
                $request = $event->getRequest();
                if ($this->shouldBeSsl($request)) {
                    $fakeRequest = clone $request;
                    $fakeRequest->server->set('HTTPS', true);

                    return new RedirectResponse($fakeRequest->getUri(), 301);
                }

                return null;
            }, static::EVENT_PRIORITY_ON_KERNEL_REQUEST);
        }

        return $eventDispatcher;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function shouldBeSsl(Request $request): bool
    {
        $requestIsSecure = $request->isSecure();
        $isSslExcludedResource = $this->isSslExcludedResource($request);

        return (!$requestIsSecure && !$isSslExcludedResource);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isSslExcludedResource(Request $request): bool
    {
        return in_array($request->getPathInfo(), $this->getConfig()->getSslExcludedResources());
    }
}
