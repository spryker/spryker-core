<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Plugin\EventDispatcher;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Shared\Kernel\ClassResolver\ResolverCacheManager;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpKernel\KernelEvents;

class AutoloaderCacheEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds a listener for the `\Symfony\Component\HttpKernel\KernelEvents::TERMINATE` event, which will write a class resolver cache.
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
        $eventDispatcher->addListener(KernelEvents::TERMINATE, function () {
            $this->persistClassResolverCache();
        });

        return $eventDispatcher;
    }

    /**
     * @return void
     */
    protected function persistClassResolverCache(): void
    {
        $resolverCacheManager = new ResolverCacheManager();

        if (!$resolverCacheManager->useCache()) {
            return;
        }

        $cacheProvider = $resolverCacheManager->createClassResolverCacheProvider();
        $cacheProvider->getCache()->persist();
    }
}
