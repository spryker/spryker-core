<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Security\Communication\Booter;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Zed\Security\Communication\Router\SecurityRouterInterface;
use Spryker\Zed\Security\Communication\Subscriber\SecurityDispatcherSubscriberInterface;

class SecurityApplicationBooter implements SecurityApplicationBooterInterface
{
    /**
     * @var \Spryker\Zed\Security\Communication\Subscriber\SecurityDispatcherSubscriberInterface
     */
    protected SecurityDispatcherSubscriberInterface $securityDispatcherSubscriber;

    /**
     * @var \Spryker\Zed\Security\Communication\Router\SecurityRouterInterface
     */
    protected SecurityRouterInterface $router;

    /**
     * @param \Spryker\Zed\Security\Communication\Subscriber\SecurityDispatcherSubscriberInterface $securityDispatcherSubscriber
     * @param \Spryker\Zed\Security\Communication\Router\SecurityRouterInterface $router
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
