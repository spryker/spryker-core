<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Customer\Plugin\EventDispatcher;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Customer\CustomerConfig;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Yves\Customer\CustomerFactory getFactory()
 */
class AnonymousIdSessionAssignEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    /**
     * @var int
     */
    protected const LISTENER_PRIORITY = 8;

    /**
     * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    public function extend(EventDispatcherInterface $eventDispatcher, ContainerInterface $container): EventDispatcherInterface
    {
        $eventDispatcher->addListener(KernelEvents::REQUEST, function (RequestEvent $event) {
            if (!$event->isMainRequest()) {
                return;
            }

            $request = $event->getRequest();
            $session = $request->getSession();

            $anonymousId = $session->get(CustomerConfig::ANONYMOUS_SESSION_KEY);
            if ($anonymousId === null) {
                $session->set(CustomerConfig::ANONYMOUS_SESSION_KEY, $this->getFactory()->createAnonymousIdProvider()->generateUniqueId());
            }
        }, static::LISTENER_PRIORITY);

        return $eventDispatcher;
    }
}
