<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Security\Booter;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Yves\Security\Router\SecurityRouterInterface;
use Spryker\Yves\Security\Subscriber\SecurityDispatcherSubscriberInterface;

class SecurityApplicationBooter implements SecurityApplicationBooterInterface
{
    /**
     * @var \Spryker\Yves\Security\Subscriber\SecurityDispatcherSubscriberInterface
     */
    protected SecurityDispatcherSubscriberInterface $securityDispatcherSubscriber;

    /**
     * @var \Spryker\Yves\Security\Router\SecurityRouterInterface
     */
    protected SecurityRouterInterface $router;

    /**
     * @param \Spryker\Yves\Security\Subscriber\SecurityDispatcherSubscriberInterface $securityDispatcherSubscriber
     * @param \Spryker\Yves\Security\Router\SecurityRouterInterface $router
     */
    public function __construct(
        SecurityDispatcherSubscriberInterface $securityDispatcherSubscriber,
        SecurityRouterInterface $router
    ) {
        $this->securityDispatcherSubscriber = $securityDispatcherSubscriber;
        $this->router = $router;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function boot(ContainerInterface $container): ContainerInterface
    {
        $this->securityDispatcherSubscriber->addSubscriber($container);
        $this->router->addRouter($container);

        return $container;
    }
}
