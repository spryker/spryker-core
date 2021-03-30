<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferWishlist;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantProductOfferWishlist\Dependency\Facade\MerchantProductOfferWishlistToMerchantFacadeBridge;
use Spryker\Zed\MerchantProductOfferWishlist\Dependency\Facade\MerchantProductOfferWishlistToProductOfferFacadeBridge;

/**
 * @method \Spryker\Zed\MerchantProductOfferWishlist\MerchantProductOfferWishlistConfig getConfig()
 */
class MerchantProductOfferWishlistDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRODUCT_OFFER = 'FACADE_PRODUCT_OFFER';
    public const FACADE_MERCHANT = 'FACADE_MERCHANT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addMerchantFacade($container);
        $container = $this->addProductOfferFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT, function (Container $container) {
            return new MerchantProductOfferWishlistToMerchantFacadeBridge(
                $container->getLocator()->merchant()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOfferFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_OFFER, function (Container $container) {
            return new MerchantProductOfferWishlistToProductOfferFacadeBridge(
                $container->getLocator()->productOffer()->facade()
            );
        });

        return $container;
    }
}
