<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferWishlistRestApi;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantProductOfferWishlistRestApi\Dependency\Facade\MerchantProductOfferWishlistRestApiToWishlistFacadeBridge;

/**
 * @method \Spryker\Zed\MerchantProductOfferWishlistRestApi\MerchantProductOfferWishlistRestApiConfig getConfig()
 */
class MerchantProductOfferWishlistRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_WISHLIST = 'FACADE_WISHLIST';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addWishlistFacade($container);

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
            return new MerchantProductOfferWishlistRestApiToWishlistFacadeBridge(
                $container->getLocator()->wishlist()->facade()
            );
        });

        return $container;
    }
}
