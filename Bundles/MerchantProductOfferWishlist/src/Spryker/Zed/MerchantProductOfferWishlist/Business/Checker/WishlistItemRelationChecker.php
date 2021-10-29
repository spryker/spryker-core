<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferWishlist\Business\Checker;

use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer;
use Generated\Shared\Transfer\WishlistPreUpdateItemCheckResponseTransfer;
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
        return (new WishlistPreAddItemCheckResponseTransfer())
            ->setIsSuccess($this->hasProductOffer($wishlistItemTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistPreUpdateItemCheckResponseTransfer
     */
    public function checkUpdateWishlistItemProductOfferRelation(
        WishlistItemTransfer $wishlistItemTransfer
    ): WishlistPreUpdateItemCheckResponseTransfer {
        return (new WishlistPreUpdateItemCheckResponseTransfer())
            ->setIsSuccess($this->hasProductOffer($wishlistItemTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return bool
     */
    protected function hasProductOffer(WishlistItemTransfer $wishlistItemTransfer): bool
    {
        if (!$wishlistItemTransfer->getProductOfferReference()) {
            return true;
        }

        /** @var string $sku */
        $sku = $wishlistItemTransfer->getSku();
        /** @var string $productOfferReference */
        $productOfferReference = $wishlistItemTransfer->getProductOfferReference();

        $productOfferCriteriaTransfer = (new ProductOfferCriteriaTransfer())
            ->addConcreteSku($sku)
            ->setProductOfferReference($productOfferReference);

        return (bool)$this->productOfferFacade->findOne($productOfferCriteriaTransfer);
    }
}
