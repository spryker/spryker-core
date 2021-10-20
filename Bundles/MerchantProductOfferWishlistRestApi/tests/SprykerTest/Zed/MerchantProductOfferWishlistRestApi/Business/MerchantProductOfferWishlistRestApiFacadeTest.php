<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOfferWishlistRestApi\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Orm\Zed\Wishlist\Persistence\SpyWishlistItemQuery;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductOfferWishlistRestApi
 * @group Business
 * @group Facade
 * @group MerchantProductOfferWishlistRestApiFacadeTest
 * Add your own group annotations below this line
 */
class MerchantProductOfferWishlistRestApiFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProductOfferWishlistRestApi\MerchantProductOfferWishlistRestApiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testDeleteWishlistItemSuccess(): void
    {
        // Arrange
        $wishlistItemCount = (new SpyWishlistItemQuery())
            ->count();
        $customerTransfer = $this->tester->haveCustomer();
        $merchantTransfer = $this->tester->haveMerchant();
        $productConcreteTransfer = $this->tester->haveProduct([
            ProductConcreteTransfer::IS_ACTIVE => true,
        ]);
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSku(),
            ProductOfferTransfer::ID_PRODUCT_CONCRETE => $productConcreteTransfer->getIdProductConcrete(),
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

        $wishlistItemRequestTransfer = (new WishlistItemRequestTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setIdCustomer($customerTransfer->getIdCustomer())
            ->setSku($wishlistItemTransfer->getSku())
            ->setUuid($wishlistItemTransfer->getSku() . '_' . $productOfferTransfer->getProductOfferReference())
            ->setUuidWishlist($wishlistTransfer->getUuid());
        $wishlistItemTransfers = new ArrayObject([$wishlistItemTransfer]);

        // Act
        $this->tester->getFacade()->deleteWishlistItem(
            $wishlistItemRequestTransfer,
            $wishlistItemTransfers,
        );

        $expectedWishlistItemCount = (new SpyWishlistItemQuery())
            ->count();

        // Assert
        $this->assertSame($wishlistItemCount, $expectedWishlistItemCount);
    }

    /**
     * @return void
     */
    public function testDeleteWishlistItemWithWrongSkuNotDeleted(): void
    {
        // Arrange
        $wishlistItemCount = (new SpyWishlistItemQuery())
            ->count();
        $customerTransfer = $this->tester->haveCustomer();
        $merchantTransfer = $this->tester->haveMerchant();
        $productConcreteTransfer = $this->tester->haveProduct([
            ProductConcreteTransfer::IS_ACTIVE => true,
        ]);
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSku(),
            ProductOfferTransfer::ID_PRODUCT_CONCRETE => $productConcreteTransfer->getIdProductConcrete(),
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

        $wishlistItemRequestTransfer = (new WishlistItemRequestTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setIdCustomer($customerTransfer->getIdCustomer())
            ->setSku('test2')
            ->setUuid('test2' . '_' . $productOfferTransfer->getProductOfferReference())
            ->setUuidWishlist($wishlistTransfer->getUuid());
        $wishlistItemTransfers = new ArrayObject([$wishlistItemTransfer]);

        // Act
        $this->tester->getFacade()->deleteWishlistItem(
            $wishlistItemRequestTransfer,
            $wishlistItemTransfers,
        );

        $expectedWishlistItemCount = (new SpyWishlistItemQuery())
            ->count();

        // Assert
        $this->assertSame($wishlistItemCount + 1, $expectedWishlistItemCount);
    }

    /**
     * @return void
     */
    public function testDeleteWishlistItemWithWrongProductOfferReferenceNotDeleted(): void
    {
        // Arrange
        $wishlistItemCount = (new SpyWishlistItemQuery())
            ->count();
        $customerTransfer = $this->tester->haveCustomer();
        $merchantTransfer = $this->tester->haveMerchant();
        $productConcreteTransfer = $this->tester->haveProduct([
            ProductConcreteTransfer::IS_ACTIVE => true,
        ]);
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSku(),
            ProductOfferTransfer::ID_PRODUCT_CONCRETE => $productConcreteTransfer->getIdProductConcrete(),
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

        $wishlistItemRequestTransfer = (new WishlistItemRequestTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setIdCustomer($customerTransfer->getIdCustomer())
            ->setSku($wishlistItemTransfer->getSku())
            ->setUuid($wishlistItemTransfer->getSku() . '_' . 'test_offer_reference')
            ->setUuidWishlist($wishlistTransfer->getUuid());
        $wishlistItemTransfers = new ArrayObject([$wishlistItemTransfer]);

        // Act
        $this->tester->getFacade()->deleteWishlistItem(
            $wishlistItemRequestTransfer,
            $wishlistItemTransfers,
        );

        $expectedWishlistItemCount = (new SpyWishlistItemQuery())
            ->count();

        // Assert
        $this->assertSame($wishlistItemCount + 1, $expectedWishlistItemCount);
    }

    /**
     * @return void
     */
    public function testDeleteWishlistItemWithoutProductOfferSuccess(): void
    {
        // Arrange
        $wishlistItemCount = (new SpyWishlistItemQuery())
            ->count();
        $customerTransfer = $this->tester->haveCustomer();
        $merchantTransfer = $this->tester->haveMerchant();
        $productConcreteTransfer = $this->tester->haveProduct([
            ProductConcreteTransfer::IS_ACTIVE => true,
        ]);
        $wishlistTransfer = $this->tester->haveWishlist([
            WishlistTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
        ]);
        $wishlistItemTransfer = $this->tester->haveItemInWishlist([
            WishlistItemTransfer::FK_WISHLIST => $wishlistTransfer->getIdWishlist(),
            WishlistItemTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
            WishlistItemTransfer::SKU => $productConcreteTransfer->getSku(),
            WishlistItemTransfer::WISHLIST_NAME => $wishlistTransfer->getName(),
            WishlistItemTransfer::PRODUCT_OFFER_REFERENCE => null,
        ]);

        $wishlistItemRequestTransfer = (new WishlistItemRequestTransfer())
            ->setIdCustomer($customerTransfer->getIdCustomer())
            ->setSku($wishlistItemTransfer->getSku())
            ->setUuid($wishlistItemTransfer->getSku())
            ->setUuidWishlist($wishlistTransfer->getUuid());
        $wishlistItemTransfers = new ArrayObject([$wishlistItemTransfer]);

        // Act
        $this->tester->getFacade()->deleteWishlistItemWithoutProductOffer(
            $wishlistItemRequestTransfer,
            $wishlistItemTransfers,
        );

        $expectedWishlistItemCount = (new SpyWishlistItemQuery())
            ->count();

        // Assert
        $this->assertSame($wishlistItemCount, $expectedWishlistItemCount);
    }

    /**
     * @return void
     */
    public function testDeleteWishlistItemWithoutProductOfferWithWrongSkuNotDeleted(): void
    {
        // Arrange
        $wishlistItemCount = (new SpyWishlistItemQuery())
            ->count();
        $customerTransfer = $this->tester->haveCustomer();
        $merchantTransfer = $this->tester->haveMerchant();
        $productConcreteTransfer = $this->tester->haveProduct([
            ProductConcreteTransfer::IS_ACTIVE => true,
        ]);
        $wishlistTransfer = $this->tester->haveWishlist([
            WishlistTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
        ]);
        $wishlistItemTransfer = $this->tester->haveItemInWishlist([
            WishlistItemTransfer::FK_WISHLIST => $wishlistTransfer->getIdWishlist(),
            WishlistItemTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
            WishlistItemTransfer::SKU => $productConcreteTransfer->getSku(),
            WishlistItemTransfer::WISHLIST_NAME => $wishlistTransfer->getName(),
        ]);

        $wishlistItemRequestTransfer = (new WishlistItemRequestTransfer())
            ->setIdCustomer($customerTransfer->getIdCustomer())
            ->setSku('test2')
            ->setUuid('test2')
            ->setUuidWishlist($wishlistTransfer->getUuid());
        $wishlistItemTransfers = new ArrayObject([$wishlistItemTransfer]);

        // Act
        $this->tester->getFacade()->deleteWishlistItemWithoutProductOffer(
            $wishlistItemRequestTransfer,
            $wishlistItemTransfers,
        );

        $expectedWishlistItemCount = (new SpyWishlistItemQuery())
            ->count();

        // Assert
        $this->assertSame($wishlistItemCount + 1, $expectedWishlistItemCount);
    }
}
