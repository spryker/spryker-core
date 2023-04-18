<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantPortalApplication\Communication\Plugin\EventDispatcher;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Zed\MerchantPortalApplication\MerchantPortalApplicationConfig getConfig()
 * @method \Spryker\Zed\MerchantPortalApplication\Communication\MerchantPortalApplicationCommunicationFactory getFactory()
 */
class HeadersSecurityEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds a listener for KernelEvents::RESPONSE event to add security headers from config.
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
        $securityHeaders = $this->getConfig()->getSecurityHeaders();

        $eventDispatcher->addListener(
            KernelEvents::RESPONSE,
            function (ResponseEvent $event) use ($securityHeaders): void {
                foreach ($securityHeaders as $securityHeaderName => $securityHeaderValue) {
                    if ($securityHeaderValue) {
                        $event->getResponse()->headers->set($securityHeaderName, $securityHeaderValue);
                    }
                }
            },
        );

        return $eventDispatcher;
    }
}
