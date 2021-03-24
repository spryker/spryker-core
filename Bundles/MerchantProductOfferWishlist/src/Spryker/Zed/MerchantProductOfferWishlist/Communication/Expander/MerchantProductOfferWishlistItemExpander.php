<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferWishlist\Communication\Expander;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Spryker\Zed\MerchantProductOfferWishlist\Dependency\Facade\MerchantProductOfferWishlistToMerchantFacadeInterface;
use Spryker\Zed\MerchantProductOfferWishlist\Dependency\Facade\MerchantProductOfferWishlistToProductOfferFacadeInterface;

class MerchantProductOfferWishlistItemExpander implements MerchantProductOfferWishlistItemExpanderInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductOfferWishlist\Dependency\Facade\MerchantProductOfferWishlistToProductOfferFacadeInterface
     */
    protected $productOfferFacade;

    /**
     * @var \Spryker\Zed\MerchantProductOfferWishlist\Dependency\Facade\MerchantProductOfferWishlistToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @param \Spryker\Zed\MerchantProductOfferWishlist\Dependency\Facade\MerchantProductOfferWishlistToProductOfferFacadeInterface $productOfferFacade
     * @param \Spryker\Zed\MerchantProductOfferWishlist\Dependency\Facade\MerchantProductOfferWishlistToMerchantFacadeInterface $merchantFacade
     */
    public function __construct(
        MerchantProductOfferWishlistToProductOfferFacadeInterface $productOfferFacade,
        MerchantProductOfferWishlistToMerchantFacadeInterface $merchantFacade
    ) {
        $this->productOfferFacade = $productOfferFacade;
        $this->merchantFacade = $merchantFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expandWishlistItem(WishlistItemTransfer $wishlistItemTransfer): WishlistItemTransfer
    {
        if (!$wishlistItemTransfer->getProductOfferReference()) {
            return $wishlistItemTransfer;
        }

        /** @var string $productOfferReference */
        $productOfferReference = $wishlistItemTransfer->getProductOfferReference();
        $productOfferCriterialFilterTransfer = (new ProductOfferCriteriaFilterTransfer())
            ->setProductOfferReference($productOfferReference);
        $productOfferTransfer = $this->productOfferFacade->findOne($productOfferCriterialFilterTransfer);

        if (!$productOfferTransfer) {
            return $wishlistItemTransfer;
        }

        $idMerchant = $productOfferTransfer->getFkMerchant();

        if (!$idMerchant) {
            return $wishlistItemTransfer;
        }

        $merchantCriteriaTransfer = (new MerchantCriteriaTransfer())
            ->setIdMerchant($idMerchant);

        $merchantTransfer = $this->merchantFacade
            ->findOne($merchantCriteriaTransfer);

        if (!$merchantTransfer) {
            return $wishlistItemTransfer;
        }

        return $wishlistItemTransfer->setMerchantReference($merchantTransfer->getMerchantReference());
    }
}
