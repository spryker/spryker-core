<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationWishlist;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToProductConfigurationClientBridge;
use Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToProductConfigurationStorageClientBridge;
use Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToWishlistClientBridge;
use Spryker\Client\ProductConfigurationWishlist\Dependency\Service\ProductConfigurationWishlistToProductConfigurationServiceBridge;

/**
 * @method \Spryker\Client\ProductConfigurationWishlist\ProductConfigurationWishlistConfig getConfig()
 */
class ProductConfigurationWishlistDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_WISHLIST = 'CLIENT_WISHLIST';

    /**
     * @var string
     */
    public const CLIENT_PRODUCT_CONFIGURATION_STORAGE = 'CLIENT_PRODUCT_CONFIGURATION_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_PRODUCT_CONFIGURATION = 'CLIENT_PRODUCT_CONFIGURATION';

    /**
     * @var string
     */
    public const SERVICE_PRODUCT_CONFIGURATION = 'SERVICE_PRODUCT_CONFIGURATION';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container = $this->addProductConfigurationStorageClient($container);
        $container = $this->addWishlistClient($container);
        $container = $this->addProductConfigurationClient($container);
        $container = $this->addProductConfigurationService($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductConfigurationStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_CONFIGURATION_STORAGE, function (Container $container) {
            return new ProductConfigurationWishlistToProductConfigurationStorageClientBridge(
                $container->getLocator()->productConfigurationStorage()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addWishlistClient(Container $container): Container
    {
        $container->set(static::CLIENT_WISHLIST, function (Container $container) {
            return new ProductConfigurationWishlistToWishlistClientBridge(
                $container->getLocator()->wishlist()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductConfigurationClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_CONFIGURATION, function (Container $container) {
            return new ProductConfigurationWishlistToProductConfigurationClientBridge(
                $container->getLocator()->productConfiguration()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductConfigurationService(Container $container): Container
    {
        $container->set(static::SERVICE_PRODUCT_CONFIGURATION, function (Container $container) {
            return new ProductConfigurationWishlistToProductConfigurationServiceBridge(
                $container->getLocator()->productConfiguration()->service(),
            );
        });

        return $container;
    }
}
