<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferWishlist\Business\Validator;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer;
use Generated\Shared\Transfer\WishlistPreUpdateItemCheckResponseTransfer;
use Spryker\Zed\MerchantProductOfferWishlist\Dependency\Facade\MerchantProductOfferWishlistToMerchantFacadeInterface;
use Spryker\Zed\MerchantProductOfferWishlist\Dependency\Facade\MerchantProductOfferWishlistToProductOfferFacadeInterface;

class WishlistItemProductOfferValidator implements WishlistItemProductOfferValidatorInterface
{
    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const MERCHANT_STATUS_APPROVED = 'approved';

    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const PRODUCT_OFFER_STATUS_APPROVED = 'approved';

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
     * @return \Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer
     */
    public function validateWishlistItemProductOfferBeforeCreation(WishlistItemTransfer $wishlistItemTransfer): WishlistPreAddItemCheckResponseTransfer
    {
        return (new WishlistPreAddItemCheckResponseTransfer())
            ->setIsSuccess($this->hasValidOffer($wishlistItemTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistPreUpdateItemCheckResponseTransfer
     */
    public function validateWishlistItemProductOfferBeforeUpdate(
        WishlistItemTransfer $wishlistItemTransfer
    ): WishlistPreUpdateItemCheckResponseTransfer {
        return (new WishlistPreUpdateItemCheckResponseTransfer())
            ->setIsSuccess($this->hasValidOffer($wishlistItemTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return bool
     */
    protected function hasValidOffer(WishlistItemTransfer $wishlistItemTransfer): bool
    {
        if (!$wishlistItemTransfer->getProductOfferReference()) {
            return true;
        }

        $sku = $wishlistItemTransfer->getSkuOrFail();
        $productOfferReference = $wishlistItemTransfer->getProductOfferReferenceOrFail();

        $productOfferCriteriaTransfer = (new ProductOfferCriteriaTransfer())
            ->setIsActive(true)
            ->addApprovalStatus(static::PRODUCT_OFFER_STATUS_APPROVED)
            ->addConcreteSku($sku)
            ->setProductOfferReference($productOfferReference);

        $productOfferTransfer = $this->productOfferFacade->findOne($productOfferCriteriaTransfer);

        if (!$productOfferTransfer) {
            return false;
        }

        $merchantReference = $productOfferTransfer->getMerchantReference();
        if (!$merchantReference) {
            return false;
        }

        return $this->isMerchantActive($merchantReference);
    }

    /**
     * @param string $merchantReference
     *
     * @return bool
     */
    protected function isMerchantActive(string $merchantReference): bool
    {
        $merchantCriteriaTransfer = (new MerchantCriteriaTransfer())
            ->setIsActive(true)
            ->setStatus(static::MERCHANT_STATUS_APPROVED)
            ->setMerchantReference($merchantReference);
        $merchantTransfer = $this->merchantFacade->findOne($merchantCriteriaTransfer);

        return $merchantTransfer !== null;
    }
}
