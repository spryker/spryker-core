<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Application\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @deprecated Use {@link \Spryker\Shared\Application\ServiceProvider\HeadersSecurityServiceProvider} instead
 */
class HeadersSecurityServiceProvider implements ServiceProviderInterface
{
    /**
     * @var string
     */
    public const HEADER_X_FRAME_OPTIONS = 'X-Frame-Options';

    /**
     * @var string
     */
    public const HEADER_CONTENT_SECURITY_POLICY = 'Content-Security-Policy';

    /**
     * @var string
     */
    public const HEADER_X_CONTENT_TYPE_OPTIONS = 'X-Content-Type-Options';

    /**
     * @var string
     */
    public const HEADER_X_XSS_PROTECTION = 'X-XSS-Protection';

    /**
     * {@inheritDoc}
     *
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
        /** @var \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher */
        $eventDispatcher = $app['dispatcher'];
        $eventDispatcher->addListener(KernelEvents::RESPONSE, [$this, 'onKernelResponse'], 0);
    }

    /**
     * Sets security headers.
     *
     * @param \Symfony\Component\HttpKernel\Event\ResponseEvent $event
     *
     * @return void
     */
    public function onKernelResponse(ResponseEvent $event)
    {
        if (!$this->isMainRequest($event)) {
            return;
        }

        $event->getResponse()->headers->set(static::HEADER_X_FRAME_OPTIONS, 'SAMEORIGIN');
        $event->getResponse()->headers->set(static::HEADER_CONTENT_SECURITY_POLICY, 'frame-ancestors \'self\'');
        $event->getResponse()->headers->set(static::HEADER_X_CONTENT_TYPE_OPTIONS, 'nosniff');
        $event->getResponse()->headers->set(static::HEADER_X_XSS_PROTECTION, '1; mode=block');
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
}
