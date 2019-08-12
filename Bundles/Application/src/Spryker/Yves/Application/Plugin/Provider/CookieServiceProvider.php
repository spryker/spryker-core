<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Application\Plugin\Provider;

use ArrayObject;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @deprecated Use `Spryker\Yves\Http\Plugin\Application\HttpApplicationPlugin` and `Spryker\Yves\Http\Plugin\EventDispatcher\CookieEventDispatcherPlugin` instead.
 */
class CookieServiceProvider implements ServiceProviderInterface
{
    /**
     * @var \Spryker\Yves\Kernel\Application
     */
    private $app;

    /**
     * @param \Spryker\Yves\Kernel\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $this->app = $app;
        $app['cookies'] = $app->share(function () {
            return new ArrayObject();
        });
    }

    /**
     * Handles transparent Cookie insertion
     *
     * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event The event to handle
     *
     * @return void
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();

        foreach ($this->app['cookies'] as $cookie) {
            $response->headers->setCookie($cookie);
        }
    }

    /**
     * @param \Spryker\Yves\Kernel\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
        $app['dispatcher']->addListener(KernelEvents::RESPONSE, [$this, 'onKernelResponse'], -255);
    }
}
