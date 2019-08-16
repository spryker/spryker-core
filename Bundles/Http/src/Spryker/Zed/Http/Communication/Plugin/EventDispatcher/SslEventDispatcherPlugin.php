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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Zed\Http\Communication\HttpCommunicationFactory getFactory()
 * @method \Spryker\Zed\Http\HttpConfig getConfig()
 */
class SslEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    protected const EVENT_PRIORITY = 255;

    protected const HEADER_YVES_REQUEST = 'X-Yves-Host';

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
                    return $this->redirectToSsl($request);
                }

                return null;
            }, static::EVENT_PRIORITY);
        }

        return $eventDispatcher;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToSsl(Request $request): RedirectResponse
    {
        $copyRequest = clone $request;
        $copyRequest->server->set('HTTPS', true);

        return new RedirectResponse($copyRequest->getUri(), 301);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function shouldBeSsl(Request $request): bool
    {
        return (!$request->isSecure() && !$this->isYvesRequest($request) && !$this->isSslExcludedResource($request));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isYvesRequest(Request $request): bool
    {
        return (bool)$request->headers->get(static::HEADER_YVES_REQUEST);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isSslExcludedResource(Request $request): bool
    {
        $resourceName = sprintf('%s/%s', $request->attributes->get('module'), $request->attributes->get('controller'));

        return in_array($resourceName, $this->getConfig()->getSslExcludedResources());
    }
}
