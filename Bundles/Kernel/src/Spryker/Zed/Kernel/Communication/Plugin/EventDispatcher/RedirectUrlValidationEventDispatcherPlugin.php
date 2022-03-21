<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Communication\Plugin\EventDispatcher;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Zed\Kernel\Communication\KernelCommunicationFactory getFactory()
 * @method \Spryker\Zed\Kernel\KernelConfig getConfig()
 * @method \Spryker\Zed\Kernel\Business\KernelFacadeInterface getFacade()
 */
class RedirectUrlValidationEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    /**
     * @var string
     */
    protected const FLAG_EVENT_BASED_REDIRECT_URL_VALIDATION_ENABLED = 'FLAG_EVENT_BASED_REDIRECT_URL_VALIDATION_ENABLED';

    /**
     * {@inheritDoc}
     * - Checks if redirect URL is in allowed list.
     * - Executed only for redirect responses.
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
        $container->set(static::FLAG_EVENT_BASED_REDIRECT_URL_VALIDATION_ENABLED, true);
        $eventDispatcher->addListener(KernelEvents::RESPONSE, function (ResponseEvent $event) {
            $this->getFactory()->createRedirectUrlValidator()->validateRedirectUrl($event);
        });

        return $eventDispatcher;
    }
}
