<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOfferWishlist\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductOfferWishlist
 * @group Business
 * @group Facade
 * @group MerchantProductOfferWishlistFacadeTest
 * Add your own group annotations below this line
 */
class MerchantProductOfferWishlistFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProductOfferWishlist\MerchantProductOfferWishlistBusinessTester
     */
    protected $tester;

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
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->customerTransfer = $this->tester->haveCustomer();
        $this->merchantTransfer = $this->tester->haveMerchant();
        $this->productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::FK_MERCHANT => $this->merchantTransfer->getIdMerchant(),
        ]);
        $this->wishlistTransfer = $this->tester->haveWishlist([
            WishlistTransfer::FK_CUSTOMER => $this->customerTransfer->getIdCustomer(),
        ]);
    }

    /**
     * @return void
     */
    public function testCheckWishlistItemProductOfferRelationSuccess(): void
    {
        // Arrange
        $wishlistItemTransfer = $this->tester->haveItemInWishlist([
            WishlistItemTransfer::FK_WISHLIST => $this->wishlistTransfer->getIdWishlist(),
            WishlistItemTransfer::FK_CUSTOMER => $this->customerTransfer->getIdCustomer(),
            WishlistItemTransfer::SKU => $this->productOfferTransfer->getConcreteSku(),
            WishlistItemTransfer::WISHLIST_NAME => $this->wishlistTransfer->getName(),
            WishlistItemTransfer::PRODUCT_OFFER_REFERENCE => $this->productOfferTransfer->getProductOfferReference(),
        ]);

        // Act
        $wishlistPreAddItemCheckResponseTransfer = $this->tester->getFacade()
            ->checkWishlistItemProductOfferRelation($wishlistItemTransfer);

        // Assert
        $this->assertTrue($wishlistPreAddItemCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckWishlistItemProductOfferRelationNotSuccess(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveProduct();

        $wishlistItemTransfer = $this->tester->haveItemInWishlist([
            WishlistItemTransfer::FK_WISHLIST => $this->wishlistTransfer->getIdWishlist(),
            WishlistItemTransfer::FK_CUSTOMER => $this->customerTransfer->getIdCustomer(),
            WishlistItemTransfer::SKU => $productTransfer->getSku(),
            WishlistItemTransfer::WISHLIST_NAME => $this->wishlistTransfer->getName(),
            WishlistItemTransfer::PRODUCT_OFFER_REFERENCE => 'TEST_PRODUCT_OFFER_REFERENCE',
        ]);

        // Act
        $wishlistPreAddItemCheckResponseTransfer = $this->tester->getFacade()
            ->checkWishlistItemProductOfferRelation($wishlistItemTransfer);

        // Assert
        $this->assertFalse($wishlistPreAddItemCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckUpdateWishlistItemProductOfferRelationSuccess(): void
    {
        // Arrange
        $wishlistItemTransfer = $this->tester->haveItemInWishlist([
            WishlistItemTransfer::FK_WISHLIST => $this->wishlistTransfer->getIdWishlist(),
            WishlistItemTransfer::FK_CUSTOMER => $this->customerTransfer->getIdCustomer(),
            WishlistItemTransfer::SKU => $this->productOfferTransfer->getConcreteSku(),
            WishlistItemTransfer::WISHLIST_NAME => $this->wishlistTransfer->getName(),
            WishlistItemTransfer::PRODUCT_OFFER_REFERENCE => $this->productOfferTransfer->getProductOfferReference(),
        ]);

        // Act
        $wishlistPreUpdateItemCheckResponseTransfer = $this->tester->getFacade()
            ->checkUpdateWishlistItemProductOfferRelation($wishlistItemTransfer);

        // Assert
        $this->assertTrue($wishlistPreUpdateItemCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckUpdateWishlistItemProductOfferRelationNotSuccess(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveProduct();

        $wishlistItemTransfer = $this->tester->haveItemInWishlist([
            WishlistItemTransfer::FK_WISHLIST => $this->wishlistTransfer->getIdWishlist(),
            WishlistItemTransfer::FK_CUSTOMER => $this->customerTransfer->getIdCustomer(),
            WishlistItemTransfer::SKU => $productTransfer->getSku(),
            WishlistItemTransfer::WISHLIST_NAME => $this->wishlistTransfer->getName(),
            WishlistItemTransfer::PRODUCT_OFFER_REFERENCE => 'TEST_PRODUCT_OFFER_REFERENCE',
        ]);

        // Act
        $wishlistPreUpdateItemCheckResponseTransfer = $this->tester->getFacade()
            ->checkUpdateWishlistItemProductOfferRelation($wishlistItemTransfer);

        // Assert
        $this->assertFalse($wishlistPreUpdateItemCheckResponseTransfer->getIsSuccess());
    }
}
