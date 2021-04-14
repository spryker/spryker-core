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
     * @return void
     */
    public function testCheckWishlistItemProductOfferRelationSuccess(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $merchantTransfer = $this->tester->haveMerchant();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
        ]);
        $wishlistTransfer = $this->tester->haveWishlist([
            WishlistTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
        ]);
        $wishlistItemTransfer = $this->tester->haveItemInWishlist([
            WishlistItemTransfer::FK_WISHLIST => $wishlistTransfer->getIdWishlist(),
            WishlistItemTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
            WishlistItemTransfer::SKU => $productOfferTransfer->getConcreteSku(),
            WishlistItemTransfer::WISHLIST_NAME => $wishlistTransfer->getName(),
            WishlistItemTransfer::PRODUCT_OFFER_REFERENCE => $productOfferTransfer->getProductOfferReference(),
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
        $customerTransfer = $this->tester->haveCustomer();
        $wishlistTransfer = $this->tester->haveWishlist([
            WishlistTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
        ]);
        $wishlistItemTransfer = $this->tester->haveItemInWishlist([
            WishlistItemTransfer::FK_WISHLIST => $wishlistTransfer->getIdWishlist(),
            WishlistItemTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
            WishlistItemTransfer::SKU => $productTransfer->getSku(),
            WishlistItemTransfer::WISHLIST_NAME => $wishlistTransfer->getName(),
            WishlistItemTransfer::PRODUCT_OFFER_REFERENCE => 'TEST_PRODUCT_OFFER_REFERENCE',
        ]);

        // Act
        $wishlistPreAddItemCheckResponseTransfer = $this->tester->getFacade()
            ->checkWishlistItemProductOfferRelation($wishlistItemTransfer);

        // Assert
        $this->assertFalse($wishlistPreAddItemCheckResponseTransfer->getIsSuccess());
    }
}
