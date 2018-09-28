<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Application\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class HeadersSecurityServiceProvider implements ServiceProviderInterface
{
    /**
     * @const string
     */
    public const HEADER_X_FRAME_OPTIONS = 'X-Frame-Options';

    /**
     * @const string
     */
    public const HEADER_X_CONTENT_TYPE_OPTIONS = 'X-Content-Type-Options';

    /**
     * @const string
     */
    public const HEADER_X_XSS_PROTECTION = 'X-XSS-Protection';

    /**
     * {@inheritdoc}
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
        $app['dispatcher']->addListener(KernelEvents::RESPONSE, [$this, 'onKernelResponse'], 0);
    }

    /**
     * Sets security headers.
     *
     * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
     *
     * @return void
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $event->getResponse()->headers->set(static::HEADER_X_FRAME_OPTIONS, 'SAMEORIGIN');
        $event->getResponse()->headers->set(static::HEADER_X_CONTENT_TYPE_OPTIONS, 'nosniff');
        $event->getResponse()->headers->set(static::HEADER_X_XSS_PROTECTION, '1; mode=block');
    }
}
