<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Http\Plugin\EventDispatcher;

use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cache-Control
 *
 * @method \Spryker\Glue\Http\HttpConfig getConfig()
 */
class CacheControlHeaderEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    /**
     * @var string
     */
    protected const CONFIG_CACHE_CONTROL_DIRECTIVE_MAX_AGE = 'max-age';

    /**
     * @var string
     */
    protected const CONFIG_CACHE_CONTROL_DIRECTIVE_S_MAX_AGE = 's-maxage';

    /**
     * @var string
     */
    protected const CONFIG_CACHE_CONTROL_DIRECTIVE_NO_CACHE = 'no-cache';

    /**
     * @var string
     */
    protected const CONFIG_CACHE_CONTROL_DIRECTIVE_MUST_REVALIDATE = 'must-revalidate';

    /**
     * @var string
     */
    protected const CONFIG_CACHE_CONTROL_DIRECTIVE_NO_STORE = 'no-store';

    /**
     * @var string
     */
    protected const CONFIG_CACHE_CONTROL_DIRECTIVE_PUBLIC = 'public';

    /**
     * @var string
     */
    protected const CONFIG_CACHE_CONTROL_DIRECTIVE_PRIVATE = 'private';

    /**
     * @var string
     */
    protected const CONFIG_CACHE_CONTROL_DIRECTIVE_NO_TRANSFORM = 'no-transform';

    /**
     * @var string
     */
    protected const CONFIG_CACHE_CONTROL_DIRECTIVE_IMMUTABLE = 'immutable';

    /**
     * @var string
     */
    protected const CONFIG_CACHE_CONTROL_STALE_WHILE_REVALIDATE = 'stale-while-revalidate';

    /**
     * @var string
     */
    protected const HEADER_CACHE_CONTROL = 'Cache-Control';

    /**
     * {@inheritDoc}
     * - Sets `Cache-Control` header to response.
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
        $eventDispatcher->addListener(KernelEvents::RESPONSE, function (ResponseEvent $event): void {
            if (!$this->isMainRequest($event)) {
                return;
            }

            $event->setResponse($this->setCacheControlHeader($event->getResponse()));
        });

        return $eventDispatcher;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\ResponseEvent $event
     *
     * @return bool
     */
    protected function isMainRequest(ResponseEvent $event): bool
    {
        if (method_exists($event, 'isMasterRequest')) {
            return $event->isMasterRequest();
        }

        return $event->isMainRequest();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function setCacheControlHeader(Response $response): Response
    {
        $cacheControlConfig = $this->getConfig()->getCacheControlConfig();

        if (!empty($cacheControlConfig[static::CONFIG_CACHE_CONTROL_DIRECTIVE_PUBLIC])) {
            $response->setPublic();
        }

        if (!empty($cacheControlConfig[static::CONFIG_CACHE_CONTROL_DIRECTIVE_PRIVATE])) {
            $response->setPrivate();
        }

        if (!empty($cacheControlConfig[static::CONFIG_CACHE_CONTROL_DIRECTIVE_MAX_AGE])) {
            $response->setMaxAge($cacheControlConfig[static::CONFIG_CACHE_CONTROL_DIRECTIVE_MAX_AGE]);
        }

        if (!empty($cacheControlConfig[static::CONFIG_CACHE_CONTROL_DIRECTIVE_S_MAX_AGE])) {
            $response->setSharedMaxAge($cacheControlConfig[static::CONFIG_CACHE_CONTROL_DIRECTIVE_S_MAX_AGE]);
        }

        if (!empty($cacheControlConfig[static::CONFIG_CACHE_CONTROL_DIRECTIVE_NO_CACHE])) {
            $response->headers->addCacheControlDirective(static::CONFIG_CACHE_CONTROL_DIRECTIVE_NO_CACHE, true);
        }

        if (!empty($cacheControlConfig[static::CONFIG_CACHE_CONTROL_DIRECTIVE_MUST_REVALIDATE])) {
            $response->headers->addCacheControlDirective(static::CONFIG_CACHE_CONTROL_DIRECTIVE_MUST_REVALIDATE, true);
        }

        if (!empty($cacheControlConfig[static::CONFIG_CACHE_CONTROL_DIRECTIVE_NO_STORE])) {
            $response->headers->addCacheControlDirective(static::CONFIG_CACHE_CONTROL_DIRECTIVE_NO_STORE, true);
        }

        if (!empty($cacheControlConfig[static::CONFIG_CACHE_CONTROL_DIRECTIVE_NO_TRANSFORM])) {
            $response->headers->addCacheControlDirective(static::CONFIG_CACHE_CONTROL_DIRECTIVE_NO_TRANSFORM, true);
        }

        if (!empty($cacheControlConfig[static::CONFIG_CACHE_CONTROL_DIRECTIVE_IMMUTABLE])) {
            $response->headers->addCacheControlDirective(static::CONFIG_CACHE_CONTROL_DIRECTIVE_IMMUTABLE, true);
        }

        if (!empty($cacheControlConfig[static::CONFIG_CACHE_CONTROL_STALE_WHILE_REVALIDATE])) {
            $response->headers->addCacheControlDirective(static::CONFIG_CACHE_CONTROL_STALE_WHILE_REVALIDATE, true);
        }

        return $response;
    }
}
