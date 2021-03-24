<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductWishlist;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantProductWishlist\Dependency\Facade\MerchantProductWishlistToMerchantProductFacadeBridge;
use Spryker\Zed\MerchantProductWishlist\Dependency\Facade\MerchantProductWishlistToProductFacadeBridge;

/**
 * @method \Spryker\Zed\MerchantProductWishlist\MerchantProductWishlistConfig getConfig()
 */
class MerchantProductWishlistDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_MERCHANT_PRODUCT = 'FACADE_MERCHANT_PRODUCT';
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addProductFacade($container);
        $container = $this->addMerchantProductFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT, function (Container $container) {
            return new MerchantProductWishlistToProductFacadeBridge(
                $container->getLocator()->product()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantProductFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_PRODUCT, function (Container $container) {
            return new MerchantProductWishlistToMerchantProductFacadeBridge(
                $container->getLocator()->merchantProduct()->facade()
            );
        });

        return $container;
    }
}
