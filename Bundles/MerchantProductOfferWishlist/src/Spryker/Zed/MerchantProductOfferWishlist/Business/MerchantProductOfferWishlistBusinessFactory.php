<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferWishlist\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantProductOfferWishlist\Business\Checker\WishlistItemRelationChecker;
use Spryker\Zed\MerchantProductOfferWishlist\Business\Checker\WishlistItemRelationCheckerInterface;
use Spryker\Zed\MerchantProductOfferWishlist\Dependency\Facade\MerchantProductOfferWishlistToProductOfferFacadeInterface;
use Spryker\Zed\MerchantProductOfferWishlist\MerchantProductOfferWishlistDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantProductOfferWishlist\MerchantProductOfferWishlistConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOfferWishlist\Persistence\MerchantProductOfferWishlistEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantProductOfferWishlist\Persistence\MerchantProductOfferWishlistRepositoryInterface getRepository()
 */
class MerchantProductOfferWishlistBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantProductOfferWishlist\Business\Checker\WishlistItemRelationCheckerInterface
     */
    public function createWishlistItemRelationChecker(): WishlistItemRelationCheckerInterface
    {
        return new WishlistItemRelationChecker(
            $this->getProductOfferFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProductOfferWishlist\Dependency\Facade\MerchantProductOfferWishlistToProductOfferFacadeInterface
     */
    public function getProductOfferFacade(): MerchantProductOfferWishlistToProductOfferFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductOfferWishlistDependencyProvider::FACADE_PRODUCT_OFFER);
    }
}
