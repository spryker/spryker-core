<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Plugin\EventDispatcher;

use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Strict-Transport-Security
 *
 * @method \Spryker\Glue\GlueApplication\GlueApplicationConfig getConfig()
 */
class ResponseSecurityHeadersEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Extends EventDispatch with a KernelEvents::RESPONSE event to add security headers from config.
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
            function (ResponseEvent $event) use ($securityHeaders) {
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
