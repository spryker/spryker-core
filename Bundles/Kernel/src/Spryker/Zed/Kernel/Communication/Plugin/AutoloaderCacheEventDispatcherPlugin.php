<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Communication\Plugin;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Zed\Kernel\Communication\KernelCommunicationFactory getFactory()
 * @method \Spryker\Zed\Kernel\KernelConfig getConfig()
 * @method \Spryker\Zed\Kernel\Business\KernelFacadeInterface getFacade()
 */
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
        $resolverCacheManager = $this->getFactory()->createResolverCacheManager();

        if (!$resolverCacheManager->useCache()) {
            return;
        }

        $cacheProvider = $resolverCacheManager->createClassResolverCacheProvider();
        $cacheProvider->getCache()->persist();
    }
}
