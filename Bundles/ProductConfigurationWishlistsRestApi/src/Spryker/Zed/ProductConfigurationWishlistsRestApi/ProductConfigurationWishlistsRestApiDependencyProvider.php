<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationWishlistsRestApi;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductConfigurationWishlistsRestApi\Dependency\Facade\ProductConfigurationWishlistsRestApiToWishlistFacadeBridge;
use Spryker\Zed\ProductConfigurationWishlistsRestApi\Dependency\Service\ProductConfigurationWishlistsRestApiToProductConfigurationServiceBridge;

/**
 * @method \Spryker\Zed\ProductConfigurationWishlistsRestApi\ProductConfigurationWishlistsRestApiConfig getConfig()
 */
class ProductConfigurationWishlistsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_WISHLIST = 'FACADE_WISHLIST';

    /**
     * @var string
     */
    public const SERVICE_PRODUCT_CONFIGURATION = 'SERVICE_PRODUCT_CONFIGURATION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addWishlistFacade($container);
        $container = $this->addProductConfigurationService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addWishlistFacade(Container $container): Container
    {
        $container->set(static::FACADE_WISHLIST, function (Container $container) {
            return new ProductConfigurationWishlistsRestApiToWishlistFacadeBridge(
                $container->getLocator()->wishlist()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductConfigurationService(Container $container): Container
    {
        $container->set(static::SERVICE_PRODUCT_CONFIGURATION, function (Container $container) {
            return new ProductConfigurationWishlistsRestApiToProductConfigurationServiceBridge(
                $container->getLocator()->productConfiguration()->service(),
            );
        });

        return $container;
    }
}
