<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Communication\Plugin\EventDispatcher;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Zed\Locale\Business\LocaleFacadeInterface getFacade()
 * @method \Spryker\Zed\Locale\Communication\LocaleCommunicationFactory getFactory()
 * @method \Spryker\Zed\Locale\LocaleConfig getConfig()
 * @method \Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface getQueryContainer()
 */
class LocaleEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    protected const SERVICE_LOCALE = 'locale';
    protected const EVENT_PRIORITY = 16;

    /**
     * {@inheritDoc}
     * - Adds locale listener.
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
        $eventDispatcher->addListener(
            KernelEvents::REQUEST,
            function (GetResponseEvent $event) use ($container) {
                $request = $event->getRequest();
                $request->setDefaultLocale($this->getLocale($container));

                $this->setRequestLocale($request);
            },
            static::EVENT_PRIORITY
        );

        return $eventDispatcher;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function setRequestLocale(Request $request): Request
    {
        $locale = $request->attributes->get('_locale');
        if ($locale) {
            $request->setLocale($locale);
        }

        return $request;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return string
     */
    protected function getLocale(ContainerInterface $container): string
    {
        return $container->get(static::SERVICE_LOCALE);
    }
}
