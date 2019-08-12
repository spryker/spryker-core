<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @deprecated Use `Spryker\Zed\Http\Communication\Plugin\EventDispatcher\HeaderEventDispatcherPlugin` instead.
 *
 * @method \Spryker\Zed\Application\Business\ApplicationFacadeInterface getFacade()
 * @method \Spryker\Zed\Application\Communication\ApplicationCommunicationFactory getFactory()
 * @method \Spryker\Zed\Application\ApplicationConfig getConfig()
 */
class HeaderServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
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
     * Sets cache control and store information in headers.
     *
     * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event A FilterResponseEvent instance
     *
     * @return void
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $store = Store::getInstance();

        $event->getResponse()->headers->set('X-Store', $store->getStoreName());
        $event->getResponse()->headers->set('X-Env', APPLICATION_ENV);
        $event->getResponse()->headers->set('X-Locale', $store->getCurrentLocale());

        $event->getResponse()->setPrivate();
        $event->getResponse()->setMaxAge(0);

        $event->getResponse()->headers->addCacheControlDirective('no-cache', true);
        $event->getResponse()->headers->addCacheControlDirective('no-store', true);
        $event->getResponse()->headers->addCacheControlDirective('must-revalidate', true);
    }
}
