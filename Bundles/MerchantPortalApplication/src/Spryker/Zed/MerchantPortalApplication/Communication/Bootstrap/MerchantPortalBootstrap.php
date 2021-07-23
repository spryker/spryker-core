<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantPortalApplication\Communication\Bootstrap;

use Spryker\Shared\MerchantPortalApplication\MerchantPortalApplicationInterface;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\BundleDependencyProviderResolverAwareTrait;
use Spryker\Zed\Kernel\Communication\FactoryResolverAwareTrait;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Dependency\Injector\DependencyInjector;

/**
 * @method \Spryker\Zed\MerchantPortalApplication\Communication\MerchantPortalApplicationCommunicationFactory getFactory()
 */
class MerchantPortalBootstrap
{
    use BundleDependencyProviderResolverAwareTrait;
    use FactoryResolverAwareTrait;

    /**
     * @return \Spryker\Shared\MerchantPortalApplication\MerchantPortalApplicationInterface
     */
    public function boot(): MerchantPortalApplicationInterface
    {
        return $this->getFactory()
            ->createMerchantPortalApplication()
            ->boot();
    }

    /**
     * @param \Spryker\Zed\Kernel\AbstractBundleDependencyProvider $dependencyProvider
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideExternalDependencies(AbstractBundleDependencyProvider $dependencyProvider, Container $container): Container
    {
        $dependencyProvider->provideCommunicationLayerDependencies($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Dependency\Injector\DependencyInjector $dependencyInjector
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Shared\Kernel\ContainerInterface|\Spryker\Zed\Kernel\Container
     */
    protected function injectExternalDependencies(DependencyInjector $dependencyInjector, Container $container)
    {
        $container = $dependencyInjector->injectCommunicationLayerDependencies($container);

        return $container;
    }
}
