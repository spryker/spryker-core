<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Plugin\EventDispatcher;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Yves\Kernel\KernelFactory getFactory()
 */
class RedirectUrlValidationEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    /**
     * @see \Spryker\Yves\Kernel\Controller\AbstractController::isUrlDomainWhitelisted()
     * @see \Spryker\Zed\Kernel\Communication\Controller\AbstractController::isUrlDomainWhitelisted()
     */
    protected const SECURED_REDIRECT_IS_HANDLED = 'SECURED_REDIRECT_IS_HANDLED';

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
        $this->getApplication()->set(static::SECURED_REDIRECT_IS_HANDLED, true);
        $eventDispatcher->addListener(KernelEvents::RESPONSE, function (FilterResponseEvent $event) {
            $this->getFactory()->createRedirectUrlValidator()->validateRedirectUrl($event);
        });

        return $eventDispatcher;
    }
}
