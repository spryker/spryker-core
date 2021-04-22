<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferWishlist\Business\Checker;

use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer;
use Spryker\Zed\MerchantProductOfferWishlist\Dependency\Facade\MerchantProductOfferWishlistToProductOfferFacadeInterface;

class WishlistItemRelationChecker implements WishlistItemRelationCheckerInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductOfferWishlist\Dependency\Facade\MerchantProductOfferWishlistToProductOfferFacadeInterface
     */
    protected $productOfferFacade;

    /**
     * @param \Spryker\Zed\MerchantProductOfferWishlist\Dependency\Facade\MerchantProductOfferWishlistToProductOfferFacadeInterface $productOfferFacade
     */
    public function __construct(MerchantProductOfferWishlistToProductOfferFacadeInterface $productOfferFacade)
    {
        $this->productOfferFacade = $productOfferFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer
     */
    public function checkWishlistItemProductOfferRelation(WishlistItemTransfer $wishlistItemTransfer): WishlistPreAddItemCheckResponseTransfer
    {
        $wishlistPreAddItemCheckResponseTransfer = (new WishlistPreAddItemCheckResponseTransfer())
            ->setIsSuccess(true);

        if (!$wishlistItemTransfer->getProductOfferReference()) {
            return $wishlistPreAddItemCheckResponseTransfer;
        }

        /** @var string $sku */
        $sku = $wishlistItemTransfer->getSku();
        /** @var string $productOfferReference */
        $productOfferReference = $wishlistItemTransfer->getProductOfferReference();

        $productOfferCriteriaTransfer = (new ProductOfferCriteriaTransfer())
            ->addConcreteSku($sku)
            ->setProductOfferReference($productOfferReference);

        $productOfferTransfer = $this->productOfferFacade->findOne($productOfferCriteriaTransfer);

        if (!$productOfferTransfer) {
            return $wishlistPreAddItemCheckResponseTransfer->setIsSuccess(false);
        }

        return $wishlistPreAddItemCheckResponseTransfer;
    }
}
