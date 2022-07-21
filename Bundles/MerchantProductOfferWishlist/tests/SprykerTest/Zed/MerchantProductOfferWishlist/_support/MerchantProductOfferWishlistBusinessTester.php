<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOfferWishlist;

use Codeception\Actor;
use Codeception\Scenario;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Zed\MerchantProductOfferWishlist\Business\MerchantProductOfferWishlistFacade getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantProductOfferWishlistBusinessTester extends Actor
{
    use _generated\MerchantProductOfferWishlistBusinessTesterActions;

    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const MERCHANT_STATUS_APPROVED = 'approved';

    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_DENIED
     *
     * @var string
     */
    protected const MERCHANT_STATUS_DENIED = 'denied';

    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const PRODUCT_OFFER_STATUS_APPROVED = 'approved';

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransfer;

    /**
     * @var \Generated\Shared\Transfer\MerchantTransfer
     */
    protected $merchantTransfer;

    /**
     * @var \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected $productOfferTransfer;

    /**
     * @var \Generated\Shared\Transfer\WishlistTransfer
     */
    protected $wishlistTransfer;

    /**
     * @param \Codeception\Scenario $scenario
     */
    public function __construct(Scenario $scenario)
    {
        parent::__construct($scenario);

        $this->customerTransfer = $this->haveCustomer();
        $this->wishlistTransfer = $this->haveWishlist([
            WishlistTransfer::FK_CUSTOMER => $this->customerTransfer->getIdCustomer(),
        ]);
    }

    /**
     * @param bool $isActive
     * @param bool $isApproved
     *
     * @return void
     */
    public function setUpMerchant(bool $isActive = true, bool $isApproved = true): void
    {
        $this->merchantTransfer = $this->haveMerchant([
            MerchantTransfer::IS_ACTIVE => $isActive,
            MerchantTransfer::STATUS => ($isApproved) ? static::MERCHANT_STATUS_APPROVED : static::MERCHANT_STATUS_DENIED,
        ]);
    }

    /**
     * @param bool $isActive
     * @param bool $isApproved
     *
     * @return void
     */
    public function setUpMerchantProductOffer(bool $isActive = true, bool $isApproved = true): void
    {
        $this->productOfferTransfer = $this->haveProductOffer([
            ProductOfferTransfer::MERCHANT_REFERENCE => $this->merchantTransfer->getMerchantReference(),
            ProductOfferTransfer::IS_ACTIVE => $isActive,
            ProductOfferTransfer::APPROVAL_STATUS => ($isApproved) ? static::PRODUCT_OFFER_STATUS_APPROVED : '',
        ]);
    }

    /**
     * @param bool $setProductOfferReference
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function createProductOfferWishlistItem(bool $setProductOfferReference = true): WishlistItemTransfer
    {
        $wishlistItemTransfer = $this->haveItemInWishlist([
            WishlistItemTransfer::FK_WISHLIST => $this->wishlistTransfer->getIdWishlist(),
            WishlistItemTransfer::FK_CUSTOMER => $this->customerTransfer->getIdCustomer(),
            WishlistItemTransfer::SKU => $this->productOfferTransfer->getConcreteSku(),
            WishlistItemTransfer::WISHLIST_NAME => $this->wishlistTransfer->getName(),
            WishlistItemTransfer::PRODUCT_OFFER_REFERENCE => ($setProductOfferReference) ?
                $this->productOfferTransfer->getProductOfferReference() : 'TEST_PRODUCT_OFFER_REFERENCE',
        ]);

        return $wishlistItemTransfer;
    }
}
